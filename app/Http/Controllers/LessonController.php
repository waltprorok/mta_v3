<?php

namespace App\Http\Controllers;

use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class LessonController extends Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {
        $lessons = [];
        $data = Lesson::query()->where('teacher_id', Auth::id())->get();

        if ($data->count()) {
            foreach ($data as $value) {
                $lessons[] = Calendar::event(
                    $value->title,
                    null,
                    new DateTime($value->start_date),
                    new DateTime($value->end_date),
                    $value->id,
                    [
                        'color' => $value->color,
                        'url' => 'students/schedule/' . $value->student_id . '/edit/' . $value->id
                    ]
                );
            }
        }

        $calendar = Calendar::addEvents($lessons)
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

        return view('webapp.calendar.index', compact('calendar'));
    }

    /**
     * @param string $fromDate
     * @param string $toDate
     * @return AnonymousResourceCollection
     */
    public function list(string $fromDate, string $toDate): AnonymousResourceCollection
    {
        return Auth::user()->isAdmin() ? $this->getAllLessonsForAdmin($fromDate, $toDate) : $this->getLessonsForTeacherId($fromDate, $toDate);
    }

    /**
     * @param Request $request
     * @param Lesson $lesson
     * @return JsonResponse
     */
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
            ->with('lessonTeacherId')
            ->whereDate('start_date', '>=', $fromDate)
            ->whereDate('start_date', '<=', $toDate)
            ->orderBy('title')
            ->orderBy('start_date')
            ->get());
    }

    /**
     * @param string $fromDate
     * @param string $toDate
     * @return AnonymousResourceCollection
     */
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
