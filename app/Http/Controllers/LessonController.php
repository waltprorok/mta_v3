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
use Illuminate\Support\Facades\Auth;
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
                    $value->title,
                    false,
                    Carbon::parse($value->start_date),
                    Carbon::parse($value->end_date),
                    $value->id,
                    [
                        'color' => $value->color,
                        'url' => 'students/schedule/' . $value->student_id . '/edit/' . $value->id
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
                'defaultView' => 'month', // 'month' for full calendar 'listWeek'
                'minTime' => '08:00:00',
                'maxTime' => '22:00:00',
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
