<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\JsonResponse;

class PaymentTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $paymentTypes = PaymentType::query()->get(['id', 'name']);

        return response()->json($paymentTypes);
    }
}
