<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Exception;
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
            foreach ($data as $key => $value) {
                $lessons[] = Calendar::event(
                    $value->title,
                    null,
                    new \DateTime($value->start_date),
                    new \DateTime($value->end_date),
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
                'defaultView' => 'listWeek', // 'month' for full calendar 'listWeek'
                'minTime' => '08:00:00',
                'maxTime' => '22:00:00',
                'fixedWeekCount' => false,
                'height' => 850,
            ]);

        return view('webapp.calendar.index', compact('calendar'));
    }
}
