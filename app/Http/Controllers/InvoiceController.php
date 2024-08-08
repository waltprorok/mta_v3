<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\LessonsInvoice;
use App\Models\Invoice;
use App\Models\Lesson;
use App\Models\Student;
use Barryvdh\DomPDF\PDF;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function storePDF(Invoice $newInvoice)
    {
        $invoice = Invoice::with('student.studentTeacher')
            ->with('lessons.billingRate')
            ->find($newInvoice->id);

        $pdf = app(PDF::class);
        $pdf->setPaper('A4');

        $pdfFile = $pdf->loadView('webapp.invoice.pdf_view', ['invoice' => $invoice]);
        Storage::disk('invoice')->put('Invoice_MTA_' . $invoice->id . '.pdf', $pdfFile->output());

        return $invoice;
//        return $pdfFile->download('Invoice_MTA_' . $invoice->id . '.pdf');
    }

    public function index(): JsonResponse
    {
        $students = Invoice::with('student', 'teacher')
            ->where('teacher_id', Auth::id())
            ->get();

        return response()->json($students);
    }

    public function getStudentSelected(int $id): JsonResponse
    {
        $student = Student::where('student_id', $id)
            ->with('lessons:id,student_id,teacher_id,billing_rate_id,invoice_id,start_date,end_date,complete')
            ->with('lessons.billingRate')
            ->with('lessons.invoice')
            ->first();

        $teacher = Student::where('student_id', $id)
            ->with('studentTeacher')
            ->first();

        $filteredLessons = $student->lessons->filter(function ($lesson) {
            return is_null($lesson->invoice);
        })->values();

        return response()->json(['lessons' => $filteredLessons, 'studentTeacher' => $teacher]);
    }

    public function createInvoice()
    {
        $student = Student::query()
            ->where('status', Student::ACTIVE)
            ->with('lessons:id,student_id,teacher_id,billing_rate_id,start_date,end_date,complete')
            ->orderBy('first_name')
            ->get();

        return response()->json($student);
    }

    public function show(int $id): View
    {
        $invoice = Invoice::with('student', 'student.studentTeacher')
            ->where('id', $id)
            ->firstOrFail();

        $lessonIds = explode(',', $invoice->lesson_id);
        $lessons = Lesson::whereIn('id', $lessonIds)->get();

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

            if ($lesson->billingRate->type == 'yearly') {
                $subTotal += $lesson->billingRate->amount / 52.14;
                $total += $lesson->billingRate->amount / 52.14;
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

            if ($lesson->billingRate->type == 'yearly') {
                $amount = $lesson->billingRate->amount / 52.14;
                return [
                    $lesson->billingRate->amount = $amount,
                ];
            }

            return $lesson;
        });

        $subTotalCalculation = $subTotal * ($discount / 100);
        $total = $total - $subTotalCalculation;
        $balanceDue = $total;

        return view('webapp.invoice.show', compact('invoice', 'lessons', 'subTotal', 'discount', 'total', 'balanceDue'));
    }

    public function store(Request $request)
    {
        $lessonIds = explode(',', $request->lesson_id);

        try {
            $newInvoice = Invoice::query()->create($request->all());

            foreach ($lessonIds as $lessonId) {
                Lesson::query()->findOrFail($lessonId)->update(['invoice_id' => $newInvoice->id]);
            }

            $invoice = $this->storePDF($newInvoice);

            Mail::to($invoice->student->email)->send(new LessonsInvoice($invoice));

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return response()->json([], Response::HTTP_CREATED);
    }

}
