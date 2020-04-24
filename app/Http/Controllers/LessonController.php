<?php

namespace App\Http\Controllers;

use App\Student;
use App\Teacher;
use Auth;
use Illuminate\Http\Request;
use Calendar;
use App\Lesson;

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
                        'color' => '#0066ff',
                        'url' => 'students/schedule/'. $value->student_id . '/edit/' . $value->id
                    ]
                );
            }
        }

        $calendar = Calendar::addEvents($lessons)
            ->setOptions([
                'firstDay' => 0,
                'editable'    => false,
                'selectable'  => true,
                'defaultView' => 'agendaWeek',
                'minTime' => '08:00:00',
                'maxTime' => '22:00:00'
            ]);

        return view('webapp.calendar.index', compact('calendar'));
    }
}
