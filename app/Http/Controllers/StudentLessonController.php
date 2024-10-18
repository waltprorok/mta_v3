<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleUpdateRequest;
use App\Http\Requests\StoreScheduleApptRequest;
use App\Mail\LessonsScheduled;
use App\Models\BillingRate;
use App\Models\BusinessHours;
use App\Models\Invoice;
use App\Models\Lesson;
use App\Models\Student;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class StudentLessonController extends Controller
{
    public function index(int $id, $day = null)
    {
        $students = Student::query()->where('id', $id)->where('teacher_id', Auth::id())->get();
        $businessHours = BusinessHours::query()->where('teacher_id', Auth::id())->get();
        $billingRates = BillingRate::query()->where('teacher_id', Auth::id())
            ->where('active', true)
            ->orderBy('default', 'desc')
            ->get();
        $lessons = Lesson::query()->where('teacher_id', Auth::id())->whereDate('start_date', $day)->orderBy('start_date')->get();
        $lastLesson = Student::with('hasOneLesson')
            ->where(['id' => $id, 'teacher_id' => Auth::id(), 'status' => Student::ACTIVE])
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
            ->with('lastLesson', $lastLesson)
            ->with('billingRates', $billingRates);
    }

    /**
     * @param int $student_id
     * @param int $id
     * @param $day
     * @return Application|Factory|View
     */
    public function show(int $student_id, int $id, $day = null): View
    {
        $students = Student::query()->where(['id' => $student_id, 'teacher_id' => Auth::id()])->get();
        $businessHours = BusinessHours::query()->where('teacher_id', Auth::id())->get();
        $lessons = Lesson::query()->where(['student_id' => $student_id, 'id' => $id, 'teacher_id' => Auth::id()])->orderBy('start_date')->with('billingRate')->get();
        $billingRates = BillingRate::query()->where('teacher_id', Auth::id())
            ->where('active', true)
            ->orderBy('default', 'desc')
            ->get();

        $startDate = $day;

        if ($day == null) {
            $lessonStartDate = '';
            foreach ($lessons as $lesson) {
                $day = Carbon::parse($lesson->start_date)->format('l');
                $lessonStartDate = Carbon::parse($lesson->start_date)->format('Y-m-d');
            }
            $allLessons = Lesson::query()->where('teacher_id', Auth::id())->whereDate('start_date', $lessonStartDate)->orderBy('start_date')->get();
        } else {
            $day = Carbon::parse($day)->format('l');
            $allLessons = Lesson::query()->where('teacher_id', Auth::id())->whereDate('start_date', $startDate)->orderBy('start_date')->get();
        }

        $allTimes = $this->getAllTimes($day, $businessHours);

        $lessonTimes = $this->getLessonTimes($allLessons, $lessons, $startDate);

        $allAvailableTimes = array_diff($allTimes, $lessonTimes);

        return view('webapp.student.scheduleEdit')
            ->with('lessons', $lessons)
            ->with('students', $students)
            ->with('allTimes', $allAvailableTimes)
            ->with('startDate', $startDate)
            ->with('billingRates', $billingRates);
    }

    public function store(StoreScheduleApptRequest $request): RedirectResponse
    {
        $begin = Carbon::parse($request->get('start_date'));
        $end = Carbon::parse($request->get('start_date'));
        $endOfMonth = Carbon::parse($end)->endOfMonth();
        $diffInDays = $begin->diffInDays($endOfMonth);
        $duration = date('H:i:s', strtotime($request->get('start_time') . ' +' . $request->get('end_time') . ' minutes'));
        $recurrence = $request->get('recurrence') == 'one' ? 1 : $diffInDays;
        $end = Carbon::parse($request->get('start_date'))->addDays($recurrence);
        $student = Student::query()->with('getTeacher')->with('parent')->findOrFail($request->get('student_id')); // needed for email
        $lessons = collect();

        for ($i = $begin; $i <= $end; $i->modify('+7 day')) {
            $lesson = new Lesson();
            $lesson->student_id = $request->get('student_id');
            $lesson->teacher_id = Auth::id();
            $lesson->billing_rate_id = $request->get('billing_rate_id');
            $lesson->title = $request->get('title');
            $lesson->color = $request->get('color');
            $lesson->start_date = $i->format('Y-m-d') . ' ' . $request->get('start_time');
            $lesson->end_date = $i->format('Y-m-d') . ' ' . $duration;
            $lesson->interval = (int)$request->get('end_time');
            $lesson->save();
            $lessons[] = $lesson;
        }

        $this->emailLessonsToStudentParent($student, $lessons);

        return redirect()->back()->with('success', ' The student has been scheduled successfully.');
    }

    public function update(ScheduleUpdateRequest $request): ?RedirectResponse
    {
        if ($request->input('action') == 'update') {
            $this->scheduleUpdate($request);
            return redirect()->back()->with('success', 'You successfully updated the student\'s lesson.');
        }

        if ($request->input('action') == 'updateRemaining') {
            $this->scheduleUpdateRemaining($request);
            return redirect()->back()->with('success', 'You successfully updated all the student\'s lessons.');
        }

        return null;
    }

    /**
     * @throws Exception
     */
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
            default:
                $today = 0;
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

    private function scheduleUpdate(Request $request): void
    {
        $duration = Carbon::parse($request->get('start_time'))->addMinutes($request->get('end_time'))->format('H:i:s');

        $lesson = Lesson::query()
            ->where([
                'student_id' => $request->get('student_id'),
                'teacher_id' => Auth::id(),
                'id' => $request->get('id')
            ])
            ->first();

        $lesson->billing_rate_id = $request->get('billing_rate_id');
        $lesson->color = $request->get('color');
        $lesson->start_date = $request->get('start_date') . ' ' . $request->get('start_time');
        $lesson->end_date = $request->get('start_date') . ' ' . $duration;
        $lesson->interval = (int)$request->get('end_time');
        $lesson->update();
    }

    private function scheduleUpdateRemaining(Request $request): void
    {
        $getLesson = Lesson::where('id', $request->get('id'))->get();
        $lessonDate = Carbon::parse($request->get('start_date'));
        $endOfMonth = Carbon::parse($lessonDate)->endOfMonth();
        $duration = Carbon::parse($request->get('start_time'))->addMinutes($request->get('end_time'))->format('H:i:s');

        // keep to email parents and students
//        $student = Student::query()->with('getTeacher')->with('parent')->findOrFail($request->get('student_id')); // needed for email

        $lessonsToBeUpdated = Lesson::query()
            ->where('student_id', $request->get('student_id'))
            ->where('teacher_id', Auth::id())
            ->whereBetween('start_date', [$lessonDate, $endOfMonth])
            ->withTrashed()
            ->get();

        $updateTheseLessons = $getLesson->merge($lessonsToBeUpdated);

        $modifyDate = Carbon::parse($request->get('start_date'));

        foreach ($updateTheseLessons as $lesson) {
            // trash last record is not in month
            // but don't update start_date
            // restore if there is one more week in month
            // add this logic 10-18-2024
            if ($modifyDate > $endOfMonth) {
                $lesson->delete();
            }
            $lesson->billing_rate_id = $request->get('billing_rate_id');
            $lesson->color = $request->get('color');
            $lesson->start_date = $modifyDate->format('Y-m-d') . ' ' . $request->get('start_time');
            $lesson->end_date = $modifyDate->format('Y-m-d') . ' ' . $duration;
            $lesson->interval = (int)$request->get('end_time');
            $lesson->update();

            $modifyDate = $modifyDate->modify('+7 day');
        }
    }

    /**
     * @param Lesson $lesson
     * @return void
     * @throws Exception
     */
    private function destroyOne(Lesson $lesson): void
    {
        $this->deleteUnPaidCreatedInvoices($lesson);

        $lesson->delete();
    }

    /**
     * @param Lesson $lesson
     * @return void
     * @throws Exception
     */
    private function destroyAll(Lesson $lesson): void
    {
        $this->deleteUnPaidCreatedInvoices($lesson);

        Lesson::query()
            ->where(['student_id' => $lesson->student_id, 'teacher_id' => Auth::id()])
            ->delete();
    }

    /**
     * @param $lesson
     * @return void
     * @throws Exception
     */
    private function destroyRemaining($lesson): void
    {
        $this->deleteUnPaidCreatedInvoices($lesson);

        Lesson::query()->where('student_id', $lesson->student_id)
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
                $lesson15Minutes = Carbon::parse($allLesson->start_date)->addMinutes(15)->format('H:i:s');
                $lesson30Minutes = Carbon::parse($allLesson->start_date)->addMinutes(30)->format('H:i:s');
                $lesson45Minutes = Carbon::parse($allLesson->start_date)->addMinutes(45)->format('H:i:s');
                $lesson60Minutes = Carbon::parse($allLesson->start_date)->addMinutes(60)->format('H:i:s');

                $lessonStartParse = Carbon::parse($allLesson->start_date);
                $lessonEndParse = Carbon::parse($allLesson->end_date);
                $diffInTime = $lessonEndParse->diffInSeconds($lessonStartParse);

                $lessonTimes[] = $lessonStart;
                switch ($diffInTime) {
                    case 900: // 15 minutes
                        break;
                    case 1800: // 30 minutes
                        $lessonTimes[] = $lesson15Minutes;
                        break;
                    case 2700:  // 45 minutes
                        $lessonTimes[] = $lesson15Minutes;
                        $lessonTimes[] = $lesson30Minutes;
                        break;
                    case 3600: // 60 minutes
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
                        unset($allTimes[$allTimeKey + 2]);
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

    /**
     * @param Lesson $lesson
     * @return void
     * @throws Exception
     */
    private function deleteUnPaidCreatedInvoices(Lesson $lesson): void
    {
        $invoices = Invoice::with('lessons')->whereHas('lessons', function ($query) use ($lesson) {
            $query->where(['student_id' => $lesson->student_id, 'teacher_id' => Auth::id(), 'is_paid' => false, 'payment' => '0']);
        })->get();

        if ($invoices) {
            foreach ($invoices as $invoice) {
                $invoice->delete();
            }
        }
    }

    /**
     * @param $student
     * @param Collection $lessons
     * @return void
     */
    private function emailLessonsToStudentParent($student, Collection $lessons): void
    {
        // student has an email and parent has an email
        if ($student->email && $student->parent) {
            if ($student->parent->email) {
                Mail::to($student->email)->cc($student->parent->email)->send(new LessonsScheduled($student, $lessons));
            }
        }

        // student does NOT have an email and parent has an email
        if ($student->email == null && $student->parent) {
            if ($student->parent->email) {
                Mail::to($student->parent->email)->send(new LessonsScheduled($student, $lessons));
            }
        }

        // student has an email and parent does not have an email
        if ($student->email && $student->parent == null) {
            Mail::to($student->email)->send(new LessonsScheduled($student, $lessons));
        }
    }
}
