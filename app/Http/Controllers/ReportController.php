<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    // TODO: update with option to change per month or year
    public function payments(): JsonResponse
    {
        $invoicePayments = DB::table('invoices')
            ->leftJoin('payment_types', 'invoices.payment_type_id', '=', 'payment_types.id')
            ->select('invoices.id',
                'invoices.payment_type_id',
                'invoices.teacher_id',
                'invoices.payment',
                'payment_types.id',
                'payment_types.name',
                DB::raw('SUM(invoices.payment) as amount')
            )
            ->where('invoices.payment', '>', '0')
            ->where('invoices.teacher_id', Auth::id())
            ->groupBy('payment_types.name')
            ->get();

        $payments = collect($invoicePayments);

        return response()
            ->json([
                'paymentTypes' => $payments->pluck('name'),
                'payments' => $payments->pluck('amount'),
            ]);
    }
}
