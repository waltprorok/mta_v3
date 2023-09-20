<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillingRateRequest;
use App\Models\BillingRate;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BillingRateController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $billingRate = collect();

        try {
            $billingRate = BillingRate::query()->where('teacher_id', Auth::id())->with('billingRate')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return response()->json($billingRate);
    }

    /**
     * @param BillingRate $billingRate
     * @return JsonResponse
     */
    public function show(BillingRate $billingRate): JsonResponse
    {
        return response()->json($billingRate);
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

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return response()->json([], Response::HTTP_CREATED);
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
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return response()->json();
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
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return response()->json($billingRate);
    }
}
