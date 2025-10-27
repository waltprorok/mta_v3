<?php

namespace App\Http\Controllers;

use App\Jobs\CancelLessonJob;
use App\Models\Holiday;
use App\Models\Lesson;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use CustodiaForks\Laravel10FullCalendar\Calendar;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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
//        $scheduled = Lesson::query()->whereIn('teacher_id', $teacherIds)->whereNotIn('student_id', $studentIds)->get();
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

//        if ($scheduled->count()) {
//            foreach ($scheduled as $value) {
//                $dates[] = Calendar::event(
//                   'Scheduled',
//                    false,
//                    $value->start_date,
//                    $value->end_date,
//                    $value->id,
//                    [
//                        'color' => '#85929E',
//                    ]
//                );
//            }
//        }

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
                    'right' => 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
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
                  this.setOption("headerToolbar", mobile
                    ? { left: "title", center: "today", right: "prev,next"  }
                    : { left: "prev,next today", center: "title", right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek" }
                );
            }',
            ]);

        return view('webapp.calendar.index')->with('calendar', $calendar);
    }

    public function household(): View
    {
        $parent = User::with('parentOfStudents')->findOrFail(Auth::id());
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

    public function getLesson(int $id): JsonResponse
    {
        $data = Lesson::query()->with('student')->findOrFail($id);

        if ($data->student->parent_id == Auth::id() || $data->student->student_id == Auth::id()) {
            return response()->json(['lesson' => $data]);
        }

        return response()->json([], Response::HTTP_UNAUTHORIZED);
    }

    public function cancelLesson(Request $request): JsonResponse
    {
        $lesson = Lesson::query()
            ->where(['id' => $request->get('id')])
            ->first();

        try {
            $lesson->update([
                'status' => $request->get('status'),
                'status_updated_at' => now()
                ]);

            CancelLessonJob::dispatch($lesson);

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }
}
