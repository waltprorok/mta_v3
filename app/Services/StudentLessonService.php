<?php

namespace App\Services;

use App\Mail\LessonsScheduled;
use App\Models\Invoice;
use App\Models\Lesson;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StudentLessonService
{
    /**
     * @param Lesson $lesson
     * @return void
     * @throws Exception|Exception
     */
    public function deleteUnPaidCreatedInvoices(Lesson $lesson): void
    {
        $invoices = Invoice::with('lessons')
            ->whereHas('lessons', function ($query) use ($lesson) {
                $query->where(['student_id' => $lesson->student_id, 'teacher_id' => Auth::id(), 'is_paid' => false, 'payment' => '0']);
            })->get();

        if ($invoices) {
            foreach ($invoices as $invoice) {
                $invoice->delete();
            }
        }
    }

    /**
     * @param $student
     * @param Collection $lessons
     * @return void
     */
    public function emailLessonsToStudentParent($student, Collection $lessons): void
    {
        try {
            // student has an email and parent has an email
            if ($student->email && $student->parent) {
                if ($student->parent->email) {
                    Mail::to($student->email)->cc($student->parent->email)->queue(new LessonsScheduled($student, $lessons));
                }
            }

            // student does NOT have an email and parent has an email
            if ($student->email == null && $student->parent) {
                if ($student->parent->email) {
                    Mail::to($student->parent->email)->queue(new LessonsScheduled($student, $lessons));
                }
            }

            // student has an email and parent does not have an email
            if ($student->email && $student->parent == null) {
                Mail::to($student->email)->queue(new LessonsScheduled($student, $lessons));
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }
    }
}
