<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillingRateRequest;
use App\Models\BillingRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BillingRateController extends Controller
{
    /**
     * Admin list of blog posts
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $paymentRate = BillingRate::all()->where('teacher_id', Auth::id());

        return response()->json($paymentRate, Response::HTTP_OK);
    }

    public function store(StoreBillingRateRequest $request): JsonResponse
    {
        BillingRate::create([
            'teacher_id' => Auth::id(),
            'type' => $request->get('type'),
            'amount' => $request->get('amount')
        ]);

        $toast = ['success' => 'Billing rate saved successfully!'];

        return response()->json($toast, Response::HTTP_CREATED);
    }

}
