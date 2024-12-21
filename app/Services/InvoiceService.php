<?php

namespace App\Services;

use App\Mail\LessonsInvoice;
use App\Models\Invoice;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{

    /**
     * @var string
     */
    private $lessonIds;

    public function __construct()
    {
        $this->lessonIds = '';
    }

    /**
     * @param $invoice
     * @param $lessons
     * @return array
     */
    public function calculateLessonTotals($invoice, $lessons): array
    {
        $subTotal = 0;
        $discount = $invoice->discount;
        $total = 0;

        // 1. calculate totals first
        foreach ($lessons as $lesson) {
            if ($lesson->billingRate->type == 'lesson') {
                $subTotal += $lesson->billingRate->amount;
                $total += $lesson->billingRate->amount;
            }

            if ($lesson->billingRate->type == 'hourly') {
                $minutes = $lesson->interval / 60;
                $subTotal += $lesson->billingRate->amount * $minutes;
                $total += $lesson->billingRate->amount * $minutes;
            }

            if ($lesson->billingRate->type == 'monthly') {
                $subTotal += $lesson->billingRate->amount;
                $total += $lesson->billingRate->amount;
                break;
            }
        }

        // 2. calculate each lesson amount
        $lessons->map(function ($lesson) use ($lessons) {
            if ($lesson->billingRate->type == 'hourly') {
                $minutes = $lesson->interval / 60;
                $amount = $lesson->billingRate->amount * $minutes;
                return [
                    $lesson->billingRate->amount = $amount,
                ];
            }

            if ($lesson->billingRate->type == 'monthly') {
                $amount = $lesson->billingRate->amount / count($lessons);
                return [
                    $lesson->billingRate->amount = $amount,
                ];
            }

            return $lesson;
        });

        return array($subTotal, $discount, $total);
    }

    /**
     * @param $invoice
     * @param $additionalEmail
     * @return void
     */
    public function emailInvoiceToStudentOrParent($invoice, $additionalEmail): void
    {
        // student does not have email but parent does have email
        if (is_null($invoice->student->email) && $additionalEmail) {
            Mail::to($additionalEmail)->queue(new LessonsInvoice($invoice));
        }  // just the student has an email
        elseif (! is_null($invoice->student->email) && is_null($additionalEmail)) {
            Mail::to($invoice->student->email)->queue(new LessonsInvoice($invoice));
        } // student and parent have an email
        elseif (! is_null($invoice->student->email) && ! is_null($additionalEmail)) {
            Mail::to($invoice->student->email)->cc($additionalEmail)->queue(new LessonsInvoice($invoice));
        } // just parent or additional has email
        elseif ($additionalEmail) {
            Mail::to($additionalEmail)->queue(new LessonsInvoice($invoice));
        }
    }

    /**
     * @param $invoice
     * @return mixed
     */
    public function getCalculatedLessonTotals($invoice)
    {
        $lessons = $this->getLessons($invoice);

        $this->calculateLessonTotals($invoice, $lessons);

        unset($invoice->lessons);
        $invoice['lessons'] = $lessons;

        return $invoice;
    }

    public function getInvoiceStudentTeacherBillingRate(Invoice $invoice)
    {
        return $invoice->with('student.getTeacher')
            ->with('lessons.billingRate')
            ->where('teacher_id', $invoice->teacher_id)
            ->findOrFail($invoice->id);
    }

    /**
     * @param $invoice
     * @return Lesson[]|Builder[]|Collection
     */
    public function getLessons($invoice)
    {
        $this->lessonIds = explode(',', $invoice->lesson_id);

        return Lesson::whereIn('id', $this->lessonIds)->withTrashed()->get();
    }
}
