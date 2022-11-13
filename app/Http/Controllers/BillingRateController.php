<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillingRateRequest;
use App\Models\BillingRate;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class BillingRateController extends Controller
{
    /**
     * Admin list of blog posts
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $paymentRate = BillingRate::all()->where('teacher_id', Auth::id());
        } catch (Exception $e) {
            Log::info($e);
        }

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
        try {
            BillingRate::create([
                'teacher_id' => Auth::id(),
                'type' => $request->get('type'),
                'amount' => $request->get('amount'),
                'description' => $request->get('description'),
            ]);

            $toast = ['success' => 'Billing rate saved successfully.'];

        } catch (Throwable $e) {
            Log::info($e);
            $toast = ['error' => $e];
        }

        return response()->json($toast, Response::HTTP_CREATED);
    }

    /**
     * @param StoreBillingRateRequest $request
     * @param BillingRate $billingRate
     * @return JsonResponse
     */
    public function update(StoreBillingRateRequest $request, BillingRate $billingRate): JsonResponse
    {
        try {
            $billingRate->update($request->all());
            $toast = ['success' => 'Billing rate has been updated.'];
        } catch (Throwable $e) {
            Log::info($e);
            $toast = ['error' => $e];
        }

        return response()->json($toast, Response::HTTP_OK);
    }

    /**
     * @param BillingRate $billingRate
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(BillingRate $billingRate): JsonResponse
    {
        try {
            $billingRate->delete();
            $toast = ['success' => 'Billing rate has been deleted.'];
        } catch (Throwable $e) {
            Log::info($e);
            $toast = ['error' => $e];
        }

        return response()->json($toast, Response::HTTP_OK);
    }
}
