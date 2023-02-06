<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleUpdateRequest;
use App\Http\Requests\StoreScheduleApptRequest;
use App\Models\BusinessHours;
use App\Models\Lesson;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLessonController extends Controller
{
    public function index($id, $day = null)
    {
        $students = Student::where('id', $id)->where('teacher_id', Auth::id())->get();
        $businessHours = BusinessHours::where('teacher_id', Auth::id())->get();
        $lessons = Lesson::where('teacher_id', Auth::id())->whereDate('start_date', $day)->orderBy('start_date', 'asc')->get();
        $lastLesson = Student::with('hasOneLesson')
            ->where('id', $id)
            ->where('teacher_id', Auth::id())
            ->where('status', Student::ACTIVE)
            ->get();

        $startDate = $day;

        $day = is_null($day) ? date('l') : Carbon::parse($day)->format('l');

        $allTimes = $this->getAllTimes($day, $businessHours);

        list($studentScheduled, $allTimes) = $this->getTimes($lessons, $id, $startDate, $day, $allTimes);

        return view('webapp.student.schedule')
            ->with('students', $students)
            ->with('businessHours', $businessHours)
            ->with('allTimes', $allTimes)
            ->with('startDate', $startDate)
            ->with('studentScheduled', $studentScheduled)
            ->with('lastLesson', $lastLesson);
    }

    public function show($student_id, $id, $day = null)
    {
        $students = Student::where('id', $student_id)->where('teacher_id', Auth::id())->get();
        $businessHours = BusinessHours::where('teacher_id', Auth::id())->get();
        $lessons = Lesson::where('student_id', $student_id)->where('id', $id)->where('teacher_id', Auth::id())->orderBy('start_date', 'asc')->get();

        $startDate = $day;

        if ($day == null) {
            $lessonStartDate = '';
            foreach ($lessons as $lesson) {
                $day = Carbon::parse($lesson->start_date)->format('l');
                $lessonStartDate = Carbon::parse($lesson->start_date)->format('Y-m-d');
            }
            $allLessons = Lesson::where('teacher_id', Auth::id())->whereDate('start_date', $lessonStartDate)->orderBy('start_date', 'asc')->get();
        } else {
            $day = Carbon::parse($day)->format('l');
            $allLessons = Lesson::where('teacher_id', Auth::id())->whereDate('start_date', $startDate)->orderBy('start_date', 'asc')->get();
        }

        $allTimes = $this->getAllTimes($day, $businessHours);

        $lessonTimes = $this->getLessonTimes($allLessons, $lessons, $startDate);

        $allAvailableTimes = array_diff($allTimes, $lessonTimes);

        return view('webapp.student.scheduleEdit')
            ->with('lessons', $lessons)
            ->with('students', $students)
            ->with('allTimes', $allAvailableTimes)
            ->with('startDate', $startDate);
    }

    public function store(StoreScheduleApptRequest $request): RedirectResponse
    {
        $begin = Carbon::parse($request->get('start_date'));
        $duration = date('H:i:s', strtotime($request->get('start_time') . ' +' . $request->get('end_time') . ' minutes'));
        $recurrence = (int)$request->get('recurrence');
        $end = Carbon::parse($request->get('start_date'))->addDays($recurrence);

        for ($i = $begin; $i <= $end; $i->modify('+7 day')) {
            $lesson = new Lesson();
            $lesson->student_id = $request->get('student_id');
            $lesson->teacher_id = Auth::id();
            $lesson->title = $request->get('title');
            $lesson->color = $request->get('color');
            $lesson->start_date = $i->format('Y-m-d') . ' ' . $request->get('start_time');
            $lesson->end_date = $i->format('Y-m-d') . ' ' . $duration;
            $lesson->interval = $request->get('end_time');
            $lesson->save();
        }

        return redirect()->back()->with('success', ' The student has been scheduled successfully.');
    }

    public function update(ScheduleUpdateRequest $request): ?RedirectResponse
    {
        if ($request->input('action') == 'update') {
            $this->scheduleUpdate($request);
            return redirect()->back()->with('success', 'You successfully updated the student\'s lesson.');
        }

        if ($request->input('action') == 'updateAll') {
            $this->scheduleUpdateAll($request);
            return redirect()->back()->with('success', 'You successfully updated all the student\'s lessons.');
        }

        return null;
    }

    public function destroy(Request $request, Lesson $id)
    {
        if ($request->input('action') == 'delete') {
            $this->destroyOne($id);

            return redirect(route('student.index'))->with('success', 'The scheduled lesson has been deleted.');
        }

        if ($request->input('action') == 'deleteRemaining') {
            $this->destroyRemaining($id);

            return redirect(route('student.index'))->with('success', 'All the remaining scheduled lessons have been deleted.');
        }

        if ($request->input('action') == 'deleteAll') {
            $this->destroyAll($id);

            return redirect(route('student.index'))->with('success', 'All the scheduled lessons have been deleted.');
        }

        return null;
    }

    /**
     * @param string $day
     * @return int
     */
    private function dayOfWeek(string $day): int
    {
        switch ($day) {
            case "Monday":
                $today = 0;
                break;
            case "Tuesday":
                $today = 1;
                break;
            case "Wednesday":
                $today = 2;
                break;
            case "Thursday":
                $today = 3;
                break;
            case "Friday":
                $today = 4;
                break;
            case "Saturday":
                $today = 5;
                break;
            case "Sunday":
                $today = 6;
                break;
        }

        return $today;
    }

    /**
     * @param Request $request
     * @return string
     * @deprecated
     * @deprecated remove function not used
     */
    private function interval(Request $request)
    {
        $start_datetime = new Carbon($request->get('start_time'));
        $end_datetime = new Carbon($request->get('end_time'));

        return $start_datetime->diff($end_datetime)->format('%i');
    }

    private function scheduleUpdate(Request $request)
    {
        $duration = Carbon::parse($request->get('start_time'))->addMinutes($request->get('end_time'))->format('H:i:s');

        $lesson = Lesson::where('student_id', $request->get('student_id'))->where('teacher_id', Auth::id())->where('id', $request->get('id'))->first();
        $lesson->id = $request->get('id');
        $lesson->student_id = $request->get('student_id');
        $lesson->teacher_id = Auth::id();
        $lesson->title = $request->get('title');
        $lesson->color = $request->get('color');
        $lesson->start_date = $request->get('start_date') . ' ' . $request->get('start_time');
        $lesson->end_date = $request->get('start_date') . ' ' . $duration;
        $lesson->interval = (int)$request->get('end_time');
        $lesson->update();
    }

    private function scheduleUpdateAll(Request $request)
    {
        $duration = Carbon::parse($request->get('start_time'))->addMinutes($request->get('end_time'))->format('H:i:s');
        $begin = Carbon::parse($request->get('start_date'));
        $lessons = Lesson::all()->where('student_id', $request->get('student_id'))->where('teacher_id', Auth::id());

        foreach ($lessons as $lesson) {
            $lesson->id = $lesson->id;
            $lesson->student_id = $request->get('student_id');
            $lesson->teacher_id = Auth::id();
            $lesson->title = $request->get('title');
            $lesson->color = $request->get('color');
            $lesson->start_date = $begin->format('Y-m-d') . ' ' . $request->get('start_time');
            $lesson->end_date = $begin->format('Y-m-d') . ' ' . $duration;
            $lesson->interval = (int)$request->get('end_time');
            $lesson->update();
            $begin = $begin->modify('+7 day');
        }
    }

    /**
     * @param $lesson
     * @return void
     */
    private function destroyOne($lesson)
    {
        $lesson->delete();
    }

    /**
     * @param $lessons
     * @return void
     */
    private function destroyAll($lessons)
    {
        Lesson::where('student_id', $lessons->student_id)
            ->where('teacher_id', Auth::id())
            ->delete();
    }

    /**
     * @param $lessons
     * @return void
     */
    private function destroyRemaining($lessons)
    {
        Lesson::where('student_id', $lessons->student_id)
            ->where('teacher_id', Auth::id())
            ->whereDate('start_date', '>=', date('Y-m-d'))
            ->delete();
    }

    /**
     * @param $day
     * @param $businessHours
     * @return array
     */
    private function getAllTimes($day, $businessHours): array
    {
        $allTimes = [];

        $thisDay = $this->dayOfWeek($day);

        $amount = -30;

        foreach ($businessHours as $businessHour) {
            if ($businessHour->open_time <= $businessHour->close_time && $thisDay == $businessHour->day) {
                $diff = Carbon::parse($businessHour->open_time)->diff(Carbon::parse($businessHour->close_time));
                $amount = $amount + ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
            }

            if ($businessHour->active == 1 && $thisDay == $businessHour->day) {
                $openingTime = Carbon::parse($businessHour->open_time);
                $allTimes[] = $openingTime->toTimeString();

                for ($i = 0; $i <= ($amount / 15); $i++) {
                    $thisOpeningTime = $openingTime->addMinutes(15);
                    $allTimes[] = $thisOpeningTime->toTimeString();
                }
            }
        }

        return $allTimes;
    }

    /**
     * @param $allLessons
     * @param $lessons
     * @param $startDate
     * @return array
     */
    private function getLessonTimes($allLessons, $lessons, $startDate): array
    {
        $lessonTimes = [];
        $studentLessonStart = '';

        foreach ($allLessons as $allLesson) {
            foreach ($lessons as $lesson) {
                $studentLessonStart = Carbon::parse($lesson->start_date)->format('Y-m-d');
            }

            $allLessonsDay = Carbon::parse($allLesson->start_date)->format('Y-m-d');

            if ($allLessonsDay == $studentLessonStart || $allLessonsDay == $startDate) {
                $lessonStart = Carbon::parse($allLesson->start_date)->format('H:i:s');
                $lesson15Minutes = Carbon::parse($allLesson->start_date)->addMinute(15)->format('H:i:s');
                $lesson30Minutes = Carbon::parse($allLesson->start_date)->addMinute(30)->format('H:i:s');
                $lesson45Minutes = Carbon::parse($allLesson->start_date)->addMinute(45)->format('H:i:s');
                $lesson60Minutes = Carbon::parse($allLesson->start_date)->addMinute(60)->format('H:i:s');

                $lessonStartParse = Carbon::parse($allLesson->start_date);
                $lessonEndParse = Carbon::parse($allLesson->end_date);
                $diffInTime = $lessonEndParse->diffInSeconds($lessonStartParse);

                $lessonTimes[] = $lessonStart;

                switch ($diffInTime) {
                    case 900:
                        break;
                    case 1800:
                        $lessonTimes[] = $lesson15Minutes;
                        break;
                    case 2700:
                        $lessonTimes[] = $lesson15Minutes;
                        $lessonTimes[] = $lesson30Minutes;
                        break;
                    case 3600:
                        $lessonTimes[] = $lesson15Minutes;
                        $lessonTimes[] = $lesson30Minutes;
                        $lessonTimes[] = $lesson45Minutes;
                        break;
                }
            }
        }

        return $lessonTimes;
    }

    /**
     * @param $lessons
     * @param $id
     * @param $startDate
     * @param $day
     * @param array $allTimes
     * @return array
     */
    private function getTimes($lessons, $id, $startDate, $day, array $allTimes): array
    {
        $studentScheduled = false;

        foreach ($lessons as $lesson) {
            $lessonDay = Carbon::parse($lesson->start_date)->format('l');
            $lessonStartDate = $lesson->start_date;
            $lessonStartTime = Carbon::parse($lesson->start_date)->format('H:i:s');
            $lessonEndTime = Carbon::parse($lesson->end_date)->format('H:i:s');
            $lessonInterval = $lesson->interval;
            $studentLessonStart = Carbon::parse($lesson->start_date)->format('Y-m-d');

            if ($lesson->student_id == $id) {
                $studentScheduled = true;
            }

            if ($startDate != $studentLessonStart) {
                continue;
            }

            if ($lessonDay == $day && $lessonStartDate) {
                // remove time for a lesson that is already booked from all times
                foreach ($allTimes as $allTimeKey => $allTime) {
                    if ($allTime == $lessonStartTime && $lessonInterval == 15) {
                        unset($allTimes[$allTimeKey]);
                        unset($allTimes[$allTimeKey + 1]);
                    }

                    if ($allTime == $lessonStartTime && $lessonInterval == 30) {
                        unset($allTimes[$allTimeKey]);
                        unset($allTimes[$allTimeKey + 1]);
                    }

                    if ($allTime == $lessonStartTime && $lessonInterval == 45) {
                        unset($allTimes[$allTimeKey]);
                        unset($allTimes[$allTimeKey + 1]);
                    }

                    if ($allTime == $lessonStartTime && $lessonInterval == 60) {
                        unset($allTimes[$allTimeKey]);
                        unset($allTimes[$allTimeKey + 1]);
                    }

                    if ($allTime == $lessonEndTime) {
                        $allTimeKey = $allTimeKey - 1;
                        unset($allTimes[$allTimeKey]);
                    }
                }
            }
        }

        return array($studentScheduled, $allTimes);
    }
}
