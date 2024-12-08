<?php

namespace App\Http\Controllers;

use App\Http\Resources\LessonResource;
use App\Models\Holiday;
use App\Models\Lesson;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use LaravelFullCalendar\Facades\Calendar;

class LessonController extends Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {
        $dates = [];
        $lessons = Lesson::query()->where('teacher_id', Auth::id())->get();
        $holidays = Holiday::query()->where('teacher_id', Auth::id())->get();

        if ($lessons->count()) {
            foreach ($lessons as $value) {
                $dates[] = Calendar::event(
                    $value->status == 'Cancelled' ? 'Cancelled | ' . $value->title : $value->title,
                    false,
                    $value->start_date,
                    $value->end_date,
                    $value->id,
                    [
                        'color' => $value->status == 'Cancelled' ? '#CD6155' : $value->color,
                        'url' => 'students/reschedule/' . $value->id
                    ]
                );
            }
        }

        if ($holidays->count()) {
            foreach ($holidays as $value) {
                $dates[] = Calendar::event(
                    $value->title,
                    $value->all_day,
                    Carbon::parse($value->start_date),
                    $value->all_day ? Carbon::parse($value->end_date)->addDay() : Carbon::parse($value->end_date),
                    $value->id,
                    [
                        'color' => $value->color,
                    ]
                );
            }
        }

        $calendar = Calendar::addEvents($dates)
            ->setOptions([
                'firstDay' => 0,
                'editable' => false,
                'selectable' => true,
                'defaultView' => Auth::user()->teacherSetting->calendar ?? 'month', // 'month' for full calendar 'listWeek', 'agendaWeek', 'agendaDay'
                'minTime' => Auth::user()->teacherSetting->calendar_min_time ?? '08:00:00',
                'maxTime' => Auth::user()->teacherSetting->calendar_max_time ?? '22:00:00',
                'fixedWeekCount' => false,
                'height' => 840,
            ]);

        return view('webapp.calendar.index')->with('calendar', $calendar);
    }

    public function list(string $fromDate, string $toDate): AnonymousResourceCollection
    {
        return Auth::user()->isAdmin() ? $this->getAllLessonsForAdmin($fromDate, $toDate) : $this->getLessonsForTeacherId($fromDate, $toDate);
    }

    public function update(Request $request, Lesson $lesson): JsonResponse
    {
        $lesson->complete = $request->get('complete');
        $lesson->save();

        return response()->json($lesson);
    }

    public function completePast(Request $request): JsonResponse
    {
        $data = $request->all();

        try {
            foreach ($data as $record) {
                $lesson = Lesson::query()
                    ->where('start_date', '<', now()->startOfDay())
                    ->find($record['id'], ['id', 'start_date', 'complete', 'status']);

                if ($lesson == null) {
                    continue;
                }

                if ($lesson->complete || $lesson->status == Lesson::STATUS[2]) {
                    continue;
                }

                $lesson->update(['complete' => true]);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }

    private function getAllLessonsForAdmin(string $fromDate, string $toDate): AnonymousResourceCollection
    {
        $fromDate = Carbon::createFromFormat('D M d Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('D M d Y', $toDate)->format('Y-m-d');

        return LessonResource::collection(Lesson::query()
            ->with('lessonTeacher')
            ->whereDate('start_date', '>=', $fromDate)
            ->whereDate('start_date', '<=', $toDate)
            ->orderBy('title')
            ->orderBy('start_date')
            ->get());
    }

    private function getLessonsForTeacherId(string $fromDate, string $toDate): AnonymousResourceCollection
    {
        $fromDate = Carbon::createFromFormat('D M d Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('D M d Y', $toDate)->format('Y-m-d');

        return LessonResource::collection(Lesson::query()
            ->where('teacher_id', Auth::id())
            ->whereDate('start_date', '>=', $fromDate)
            ->whereDate('start_date', '<=', $toDate)
            ->orderBy('title')
            ->orderBy('start_date')
            ->get());
    }
}
