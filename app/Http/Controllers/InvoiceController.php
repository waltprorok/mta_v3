<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(): JsonResponse
    {
        $students = Invoice::with('student', 'teacher')
            ->where('teacher_id', Auth::id())
            ->get();

        return response()->json($students);
    }

    public function show($id)
    {
        $student = Student::with('lessons', 'studentTeacher')
            ->where('student_id', $id)
            ->get();

        $subTotal = 0;
        $discount = 0;
        $total = 0;

        foreach ($student as $data) {
            foreach ($data->lessons as $lesson) {
                $subTotal += $lesson->billingRate->amount;
                $total += $lesson->billingRate->amount;

                if ($lesson->billingRate->type == 'monthly') {
                    break;
                }

            }
        }

        $subTotalCalculation = $subTotal * ($discount / 100);

        $total = $total - $subTotalCalculation;

        $balanceDue = $total;

        return view('webapp.invoice.show', compact('student', 'subTotal', 'discount', 'total', 'balanceDue'));
    }

}
