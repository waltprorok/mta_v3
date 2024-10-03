<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillingRateRequest;
use App\Models\BillingRate;
use App\Models\Plan;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BillingRateController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $billingRate = BillingRate::query()
                ->where('teacher_id', Auth::id())
                ->with('billingRate')
                ->orderBy('active', 'desc')
                ->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($billingRate);
    }

    public function show(BillingRate $billingRate): JsonResponse
    {
        return response()->json($billingRate);
    }

    public function store(StoreBillingRateRequest $request): JsonResponse
    {
        try {
            BillingRate::query()->create([
                'teacher_id' => Auth::id(),
                'type' => $request->get('type'),
                'amount' => $request->get('amount'),
                'description' => $request->get('description'),
                'default' => $request->get('default'),
                'active' => $request->get('active'),
            ]);

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function update(StoreBillingRateRequest $request, BillingRate $billingRate): JsonResponse
    {
        try {
            $billingRate->update($request->all());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }

    /**
     * @throws Exception
     */
    public function destroy(BillingRate $billingRate): JsonResponse
    {
        try {
            $billingRate->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($billingRate);
    }

    public function billingPlans()
    {
        try {
            $plans = Plan::all(['id', 'name', 'slug', 'stripe_plan', 'cost', 'description', 'created_at']);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($plans);
    }
}
