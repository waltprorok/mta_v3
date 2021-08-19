<?php

namespace App\Http\Controllers;

use App\Lesson;
use Auth;
use Calendar;

class LessonController extends Controller
{
    public function index()

    {
        $lessons = [];
        $data = Lesson::where('teacher_id', Auth::id())->get();

        if($data->count()){
            foreach ($data as $key => $value) {
                $lessons[] = Calendar::event(
                    $value->title,
                    null,
                    new \DateTime($value->start_date),
                    new \DateTime($value->end_date),
                    $value->id,
                    [
                        'color' => $value->color,
                        'url' => 'students/schedule/'. $value->student_id . '/edit/' . $value->id
                    ]
                );
            }
        }

        $calendar = Calendar::addEvents($lessons)
            ->setOptions([
                'firstDay'    => 0,
                'editable'    => false,
                'selectable'  => true,
                'defaultView' => 'listWeek', // 'month' for full calendar
                'minTime'     => '08:00:00',
                'maxTime'     => '22:00:00',
                'fixedWeekCount' => false,
            ]);

        return view('webapp.calendar.index', compact('calendar'));
    }
}
