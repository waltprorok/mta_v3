<?php

namespace App\Http\Controllers;

use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
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
        $data = Lesson::where('teacher_id', Auth::id())->get();

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
                'height' => 760,
            ]);

        return view('webapp.calendar.index', compact('calendar'));
    }

    public function list(): AnonymousResourceCollection
    {
        return Auth::user()->isAdmin() ? $this->getAllLessonsForAdmin() : $this->getLessonsForTeacherId();
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

        return response()->json($lesson, Response::HTTP_OK);
    }

    private function getAllLessonsForAdmin(): AnonymousResourceCollection
    {
        return LessonResource::collection(Lesson::with('lessonTeacherId')->orderBy('title')->orderBy('start_date')->get());
    }

    private function getLessonsForTeacherId(): AnonymousResourceCollection
    {
        return LessonResource::collection(Lesson::where('teacher_id', Auth::id())->orderBy('title')->orderBy('start_date')->get());
    }
}
