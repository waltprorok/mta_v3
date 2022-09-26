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

    /**
     * @param BillingRate $billingRate
     * @return JsonResponse
     */
    public function show(BillingRate $billingRate): JsonResponse
    {
        return response()->json($billingRate, Response::HTTP_OK);
    }

    /**
     * @param StoreBillingRateRequest $request
     * @return JsonResponse
     */
    public function store(StoreBillingRateRequest $request): JsonResponse
    {
        BillingRate::create([
            'teacher_id' => Auth::id(),
            'type' => $request->get('type'),
            'amount' => $request->get('amount'),
            'description' => $request->get('description'),
        ]);

        $toast = ['success' => 'Billing rate saved successfully!'];

        return response()->json($toast, Response::HTTP_CREATED);
    }

    /**
     * @param StoreBillingRateRequest $request
     * @param BillingRate $billingRate
     * @return JsonResponse
     */
    public function update(StoreBillingRateRequest $request, BillingRate $billingRate): JsonResponse
    {
        $billingRate->update($request->all());

        $toast = ['success' => 'Billing rate has been updated!'];

        return response()->json($toast, Response::HTTP_OK);
    }

    public function destroy(BillingRate $billingRate): JsonResponse
    {
        $billingRate->delete();

        $toast = ['success' => 'Billing rate has been deleted!'];

        return response()->json($toast, Response::HTTP_OK);
    }

}
