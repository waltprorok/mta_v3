<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = DB::table('invoices')
            ->join('students', 'students.id', '=', 'invoices.student_id')
            ->join('payment_types', 'payment_types.id', '=', 'invoices.payment_type_id')
            ->select('invoices.*',
                'invoices.id as v_id',
                'invoices.is_paid as v_is_paid',
                'invoices.created_at as i_created_at',
                'invoices.created_at as i_updated_at',
                'students.parent_id',
                'students.student_id as s_id',
                'payment_types.*')
            ->where('v_is_paid', false) // TODO: set to true
            ->where('parent_id', Auth::user()->id)
            ->orWhere('s_id', Auth::user()->id)
            ->get();

        return response()->json(['invoices' => $payments]);

    }
}
