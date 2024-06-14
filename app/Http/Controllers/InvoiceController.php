<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvoiceController extends Controller
{
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
            ->with('lessons:id,student_id,teacher_id,billing_rate_id,start_date,end_date,complete')
            ->with('lessons.billingRate')
            ->with('studentTeacher')
            ->first();

        return response()->json($student);
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
        $discount = 0;
        $total = 0;

        foreach ($lessons as $lesson) {
            $subTotal += $lesson->billingRate->amount;
            $total += $lesson->billingRate->amount;

            if ($lesson->billingRate->type == 'monthly') {
                break;
            }

        }

        $subTotalCalculation = $subTotal * ($discount / 100);

        $total = $total - $subTotalCalculation;

        $balanceDue = $total;

        return view('webapp.invoice.show', compact('invoice', 'lessons', 'subTotal', 'discount', 'total', 'balanceDue'));
    }

}
