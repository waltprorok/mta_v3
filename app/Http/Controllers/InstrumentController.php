<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class InstrumentController extends Controller
{
    public function index()
    {
        try {
            $instruments = Instrument::query()
                ->where('teacher_id', Auth::id())
                ->orderBy('name')
                ->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($instruments);
    }

    public function show(Instrument $instrument): JsonResponse
    {
        return response()->json($instrument);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            Instrument::query()->create([
                'teacher_id' => Auth::id(),
                'name' => ucwords($request->get('name')),
            ]);

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function update(Request $request, Instrument $instrument): JsonResponse
    {
        try {
            $instrument->update([
                'name' => ucwords($request->get('name')),
            ]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }

    /**
     * @param Instrument $instrument
     * @return JsonResponse
     */
    public function destroy(Instrument $instrument): JsonResponse
    {
        try {
            $instrument->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($instrument);
    }
}
