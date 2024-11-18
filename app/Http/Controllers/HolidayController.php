<?php

namespace App\Http\Controllers;


use App\Models\Holiday;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{
    public function index()
    {
        return Holiday::query()
            ->select('id', 'title', 'start_date', 'end_date', 'color', 'all_day')
            ->where('teacher_id', Auth::user()->id)
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function show(Holiday $holiday): JsonResponse
    {
        return response()->json($holiday);
    }

    public function store(Request $request): JsonResponse
    {

        $start_date = Carbon::parse($request->get('start_date'))->tz('America/New_York')->format('Y-m-d H:i:s');
        $end_date = Carbon::parse($request->get('end_date'))->tz('America/New_York')->format('Y-m-d H:i:s');

        try {
            $holiday = new Holiday();
            $holiday->teacher_id = Auth::id();
            $holiday->title = $request->get('title');
            $holiday->color = $request->get('color');
            $holiday->start_date = $start_date;
            $holiday->end_date = $end_date;
            $holiday->all_day = $request->get('all_day');
            $holiday->save();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function update(Request $request, Holiday $holiday): JsonResponse
    {
        $start_date = Carbon::parse($request->get('start_date'))->tz('America/New_York')->format('Y-m-d H:i:s');
        $end_date = Carbon::parse($request->get('end_date'))->tz('America/New_York')->format('Y-m-d H:i:s');

        try {
            $holiday->update([
                'title' => $request->get('title'),
                'color' => $request->get('color'),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'all_day' => $request->get('all_day') == null ? false : true
            ]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }


    /**
     * @throws Exception
     */
    public function destroy(Holiday $holiday): JsonResponse
    {
        try {
            $holiday->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }
}
