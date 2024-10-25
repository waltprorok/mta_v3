<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Student;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use LaravelFullCalendar\Facades\Calendar;

class StudentUserController extends Controller
{
    /**
     * Student User Calendar Instance
     *
     * @throws Exception
     */
    public function calendar()
    {
        $lessons = [];
        $studentId = [];

        $getStudent = Student::query()
            ->where('student_id', Auth::id())
            ->get();

        foreach ($getStudent as $student) {
            $studentId[] = $student->id;
        }

        $data = Lesson::query()->where('student_id', $studentId)->get();

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
//                        'url' => 'students/schedule/' . $value->student_id . '/edit/' . $value->id
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

        return view('webapp.calendar.index')->with('calendar', $calendar);
    }
}
