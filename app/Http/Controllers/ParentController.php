<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Lesson;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use LaravelFullCalendar\Facades\Calendar;

class ParentController extends Controller
{
    /**
     * @throws Exception
     */
    public function calendar()
    {
        $dates = [];
        $studentIds = [];
        $teacherIds = [];
        $parent = User::query()
            ->with('parentOfStudents')
            ->where('id', Auth::user()->id)
            ->first();

        foreach ($parent->parentOfStudents as $student) {
            $studentIds[] = $student->id;
            $teacherIds[] = $student->teacher_id;
        }

        $lessons = Lesson::query()->whereIn('student_id', $studentIds)->get();
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

    public function household(): View
    {
        $parent = User::with('parentOfStudents')->findOrFail(Auth::id());
//        $students = $parent->parentOfStudents()->get();
        $teacher = [];

        foreach ($parent->parentOfStudents as $student) {
            $teacher = Teacher::query()
                ->where('teacher_id', $student->teacher_id)
                ->first();
        }

        return view('webapp.parent.household')
            ->with('parent', $parent)
            ->with('teacher', $teacher);
    }


}
