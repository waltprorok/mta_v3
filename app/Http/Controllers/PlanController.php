<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    public function index()
    {
        try {
            $plans = Plan::select([
                'id',
                'name',
                'slug',
                'stripe_plan',
                'cost',
                'description',
                'created_at'
            ])->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($plans);
    }
}
