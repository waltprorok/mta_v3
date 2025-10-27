<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Lesson;
use App\Models\Student;
use Carbon\Carbon;
use CustodiaForks\Laravel10FullCalendar\Calendar;
use Exception;
use Illuminate\Support\Facades\Auth;

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
                    $value->status == 'Cancelled' ? 'Cancelled | ' . $value->title : $value->title,
                    false,
                    $value->start_date,
                    $value->end_date,
                    $value->id,
                    [
                        'color' => $value->status == 'Cancelled' ? '#CD6155' : $value->color,
                        'url' => 'lesson/get/' . $value->id
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

        $calendar = new Calendar();
        $calendar->addEvents($dates)
            ->setOptions([
                'firstDay' => 0,
                'editable' => false,
                'selectable' => true,
                'initialView' => 'dayGridMonth',
                'headerToolbar' => [
                    'left' => 'prev,next today',
                    'center' => 'title',
                    'right' => 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                ],
                'slotMinTime' => '08:00:00',
                'slotMaxTime' => '22:00:00',
                'fixedWeekCount' => false,
                'height' => 760,
            ])->setCallbacks([
                // On first render, switch to list view if on a small screen
                'datesSet' => 'function() {
                const mobile = window.matchMedia("(max-width: 576px)").matches;
                if (mobile && this.view.type !== "listWeek") this.changeView("listWeek");
                this.setOption("headerToolbar", mobile
                    ? { left: "title", center: "today", right: "prev,next" }
                    : { left: "today prev,next", center: "title", right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek" }
                    );
            }',
                // On resize, toggle between compact and full views
                'windowResize' => 'function() {
                const mobile = window.matchMedia("(max-width: 576px)").matches;
                const target = mobile ? "listWeek" : "dayGridMonth";
                if (this.view.type !== target) this.changeView(target);
            }',
            ]);

        return view('webapp.calendar.index')->with('calendar', $calendar);
    }
}
