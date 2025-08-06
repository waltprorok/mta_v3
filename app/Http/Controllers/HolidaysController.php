<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Carbon\Carbon;
use Cmixin\BusinessDay;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HolidaysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userHolidays = Holiday::where('teacher_id', Auth::id())
            ->whereYear('start_date', Carbon::now()->year)
            ->get(['id', 'teacher_id', 'title', 'start_date', 'short_name']);

        BusinessDay::enable('Carbon\Carbon');
        Carbon::setHolidaysRegion('us-national');

        $holidays = collect();

        foreach (Carbon::getYearHolidays() as $id => $holiday) {
            if ($userHolidays->contains('short_name', $id)) {
                $usHoliday = $userHolidays->where('short_name', $id)->first();
                $holidays->push(['id' => $usHoliday->id, 'short_name' => $id, 'name' => $holiday->getHolidayName(), 'date' => $holiday->format('M j, Y'), 'set' => true]);
            } else {
                $holidays->push(['id' => null, 'short_name' => $id, 'name' => $holiday->getHolidayName(), 'date' => $holiday->format('M j, Y'), 'set' => false]);
            }
        }

        return response()->json($holidays);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $start_date = Carbon::parse($request->get('date'))->tz('America/New_York')->format('Y-m-d H:i:s');
        $end_date = Carbon::parse($request->get('date'))->tz('America/New_York')->format('Y-m-d H:i:s');

        try {
            $holiday = new Holiday();
            $holiday->teacher_id = Auth::id();
            $holiday->title = $request->get('name');
            $holiday->color = '#5499C7';
            $holiday->start_date = $start_date;
            $holiday->end_date = $end_date;
            $holiday->all_day = true;
            $holiday->short_name = $request->get('short_name');
            $holiday->save();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
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
