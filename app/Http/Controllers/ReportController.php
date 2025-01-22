<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PaymentType;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function status(): JsonResponse
    {
        $studentActiveCount = Student::query()
            ->where('teacher_id', Auth::id())
            ->where('status', Student::ACTIVE)
            ->count();

        $studentInActiveCount = Student::query()
            ->where('teacher_id', Auth::id())
            ->where('status', Student::INACTIVE)
            ->count();

        $studentLeadCount = Student::query()
            ->where('teacher_id', Auth::id())
            ->where('status', Student::LEAD)
            ->count();

        $studentWaitlistCount = Student::query()
            ->where('teacher_id', Auth::id())
            ->where('status', Student::WAITLIST)
            ->count();

        return response()
            ->json([
                $studentActiveCount,
                $studentInActiveCount,
                $studentLeadCount,
                $studentWaitlistCount
            ]);
    }

    public function payments(): JsonResponse
    {
        $invoicePayments = Invoice::query()
            ->with('paymentType:id,name,created_at')
            ->where('teacher_id', Auth::id())
            ->where('payment', '>', '0')
            ->get(['id', 'teacher_id', 'payment_type_id', 'payment']);

        $payments = [];

        foreach (PaymentType::all() as $type) {
            foreach ($invoicePayments as $invoice) {
                if ($invoice->payment_type_id == $type->id) {
                    $payments[] = ['type' => $type->name, 'amount' => $invoice->payment];
                }
            }
        }

        return response()
            ->json([
                'paymentTypes' => collect($payments)->pluck('type'),
                'payments' => collect($payments)->pluck('amount'),
            ]);
    }
}
