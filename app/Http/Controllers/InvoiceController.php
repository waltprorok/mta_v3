<?php

namespace App\Http\Controllers;

use App\Models\Student;

class InvoiceController extends Controller
{
    public function index()
    {
        $student = Student::with('lessons', 'studentTeacher')
            ->where('first_name', '=', 'Alden')
            ->get();

        $subTotal = 0;
        $discount = 10;
        $total = 0;

        foreach ($student as $data) {
            foreach ($data->lessons as $lesson) {
                $subTotal += $lesson->billingRate->amount;
                $total += $lesson->billingRate->amount;
            }
        }

        $subTotalCalculation = $subTotal * ($discount / 100);

        $total = $total - $subTotalCalculation;

        $balanceDue = $total;

        return view('webapp.invoice.invoice', compact('student', 'subTotal', 'discount', 'total', 'balanceDue'));
    }

}
