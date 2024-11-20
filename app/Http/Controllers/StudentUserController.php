<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Lesson;
use App\Models\Student;
use Carbon\Carbon;
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
        $dates = [];
        $studentId = [];
        $teacherIds = [];
        $getStudent = Student::query()
            ->where('student_id', Auth::id())
            ->get();

        foreach ($getStudent as $student) {
            $studentId[] = $student->id;
            $teacherIds[] = $student->teacher_id;
        }

        $lessons = Lesson::query()->where('student_id', $studentId)->get();
        $holidays = Holiday::query()->whereIn('teacher_id', $teacherIds)->get();

        if ($lessons->count()) {
            foreach ($lessons as $value) {
                $dates[] = Calendar::event(
                    $value->title,
                    false,
                    $value->start_date,
                    $value->end_date,
                    $value->id,
                    [
                        'color' => $value->color,
//                        'url' => 'students/schedule/' . $value->student_id . '/edit/' . $value->id
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
                'height' => 760,
            ]);

        return view('webapp.calendar.index')->with('calendar', $calendar);
    }
}
