<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Teacher;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class ParentController extends Controller
{
    /**
     * @return View
     */
    public function household(): View
    {
        $parent = User::with('parentOfStudent')->findOrFail(Auth::id()); // uses pivot table
        $students = $parent->parentOfStudent()->get();
        $teacher = [];

        foreach ($students as $student) {
            $teacher = Teacher::where('teacher_id', $student->teacher_id)->first();
        }

        return view('webapp.parent.household')
            ->with('parent', $parent)
            ->with('teacher', $teacher);
    }

    /**
     * @throws Exception
     */
    public function calendar()
    {
        $lessons = [];
        $studentIds = [];

        $parent = User::with('parentOfStudent')->findOrFail(Auth::id()); // uses pivot table

        foreach ($parent->parentOfStudent as $studentId) {
            $studentIds[] = $studentId->id;
        }

        $data = Lesson::whereIn('student_id', $studentIds)->get();

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
