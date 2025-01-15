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
            $plans = Plan::all(['id', 'name', 'slug', 'stripe_plan', 'cost', 'description', 'created_at']);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($plans);
    }
}
