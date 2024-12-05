<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleUpdateRequest;
use App\Http\Requests\StoreScheduleApptRequest;
use App\Models\BillingRate;
use App\Models\BusinessHours;
use App\Models\Holiday;
use App\Models\Lesson;
use App\Models\Student;
use App\Services\StudentLessonService;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentLessonController extends Controller
{
    /**
     * @var StudentLessonService
     */
    private $studentLessonService;

    public function __construct(StudentLessonService $studentLessonService)
    {
        $this->studentLessonService = $studentLessonService;
    }

    public function index($id)
    {
        $student = Student::query()->where('id', $id)->where('teacher_id', Auth::id())->firstOrFail();

        return view('webapp.student.schedule', compact('student'));
    }

    public function getStudent(int $id, $day = null): JsonResponse
    {
        $student = Student::query()
            ->with('hasOneLesson')
            ->where(['id' => $id, 'teacher_id' => Auth::id()])
            ->first();
        $businessHours = BusinessHours::query()
            ->where('teacher_id', Auth::id())
            ->get();
        $billingRates = BillingRate::getTeacherActiveRates()->get();
        $holidays = Holiday::getTeacherHolidaysForTwoYears()->get();
        $lessons = Lesson::query()
            ->where('teacher_id', Auth::id())
            ->whereDate('start_date', $day)
            ->orderBy('start_date')
            ->get();

        $startDate = $day;
        $day = is_null($day) ? date('l') : Carbon::parse($day)->format('l');
        $allTimes = $this->getAllTimes($day, $businessHours);
        list($studentScheduled, $allTimes) = $this->getTimes($lessons, $id, $startDate, $day, $allTimes);

        return response()->json([
            'student' => $student,
            'businessHours' => $businessHours,
            'billingRates' => $billingRates,
            'holidays' => $holidays,
            'studentScheduled' => $studentScheduled,
            'allTimes' => $allTimes,
        ]);
    }

    public function show($id)
    {
        $lesson = Lesson::query()->where('id', $id)->where('teacher_id', Auth::id())->firstOrFail();
        $student = $lesson->student;

        return view('webapp.student.reschedule', compact('student'));
    }

    /**
     * @param int $id
     * @param $day
     * @return JsonResponse
     */
    public function getLessonReschedule(int $id, $day = null)
    {
        $lessons = Lesson::query()
            ->with('student')
            ->where(['id' => $id, 'teacher_id' => Auth::id()])
            ->orderBy('start_date')
            ->with('billingRate')
            ->get();
        $businessHours = BusinessHours::query()
            ->where('teacher_id', Auth::id())
            ->get();
        $billingRates = BillingRate::getTeacherActiveRates()->get();
        $holidays = Holiday::getTeacherHolidaysForTwoYears()->get();
        $startDate = $day;

        if ($day == null) {
            $lessonStartDate = '';
            foreach ($lessons as $lesson) {
                $day = Carbon::parse($lesson->start_date)->format('l');
                $lessonStartDate = Carbon::parse($lesson->start_date)->format('Y-m-d');
            }

            $allLessons = Lesson::query()->where('teacher_id', Auth::id())
                ->where('status', '!=', Lesson::STATUS[2])->whereDate('start_date', $lessonStartDate)
                ->orderBy('start_date')->get();
        } else {
            $day = Carbon::parse($day)->format('l');
            $allLessons = Lesson::query()->where('teacher_id', Auth::id())
                ->where('status', '!=', Lesson::STATUS[2])->whereDate('start_date', $startDate)
                ->orderBy('start_date')->get();
        }

        $allTimes = $this->getAllTimes($day, $businessHours);
        $lessonTimes = $this->getLessonTimes($allLessons, $lessons, $startDate);
        $allAvailableTimes = array_diff($allTimes, $lessonTimes);

        $lesson = $lessons->first();

        return response()->json([
            'lesson' => $lesson,
            'allTimes' => $allAvailableTimes,
            'startDate' => $startDate,
            'businessHours' => $businessHours,
            'holidays' => $holidays,
            'billingRates' => $billingRates,
        ]);
    }

    public function store(StoreScheduleApptRequest $request): JsonResponse
    {
        $begin = Carbon::parse($request->get('start_date'));
        $endOfMonth = Carbon::parse($begin)->endOfMonth();
        $diffInDays = $begin->diffInDays($endOfMonth);
        $duration = $this->interval($request->get('start_time'), $request->get('end_time'));
        $recurrence = $request->get('recurrence') == Lesson::RECURRENCE[0] ? 1 : $diffInDays;
        $end = Carbon::parse($request->get('start_date'))->addDays($recurrence);
        $student = Student::query()
            ->with('getTeacher')
            ->with('parent')
            ->findOrFail($request->get('student_id')); // needed for email
        $holidays = Holiday::getTeacherHolidaysForTwoYears()->get();
        $lessons = collect();

        try {
            for ($i = $begin; $i <= $end; $i->modify('+7 day')) {
                $lesson = new Lesson();
                $lesson->student_id = $request->get('student_id');
                $lesson->teacher_id = Auth::id();
                $lesson->billing_rate_id = $request->get('billing_rate_id');
                $lesson->title = $request->get('title');
                $lesson->color = $request->get('color');
                $lesson->start_date = $i->format('Y-m-d') . ' ' . $request->get('start_time');

                $skipOverSave = false;

                foreach ($holidays as $holiday) {
                    $holidayDates = CarbonPeriod::create($holiday->start_date, $holiday->end_date);
                    foreach ($holidayDates->toArray() as $date) {
                        if (! is_null($date) && $date->toDateString() == Carbon::parse($lesson->start_date)->format('Y-m-d')) {
                            $holiday['start_date'] = $i->format('Y-m-d');
                            $lessons[] = $holiday->toArray();
                            $skipOverSave = true;
                        }
                    }
                }

                if ($skipOverSave) {
                    continue;
                }

                $lesson->end_date = $i->format('Y-m-d') . ' ' . $duration;
                $lesson->interval = (int)$request->get('end_time');
                $lesson->recurrence = $request->get('recurrence') == Lesson::RECURRENCE[0] ? Lesson::RECURRENCE[0] : Lesson::RECURRENCE[1];
                $lesson->save();
                $lessons[] = $lesson;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        $this->studentLessonService->emailLessonsToStudentParent($student, $lessons);

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * @param ScheduleUpdateRequest $request
     * @return JsonResponse
     */
    public function update(ScheduleUpdateRequest $request): JsonResponse
    {
        try {
            $this->scheduleUpdate($request);
            // TODO: email student / parent of lesson change
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * @param Lesson $id
     * @return JsonResponse
     */
    public function destroy(Lesson $id): JsonResponse
    {
        try {
            $this->destroyOne($id);
            // TODO: email student / parent of lesson change
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * @param $day
     * @param $businessHours
     * @return array
     */
    private function getAllTimes($day, $businessHours): array
    {
        $allTimes = [];
        $dayOfWeek = Carbon::parse($day)->dayOfWeek;
        $amount = -30;

        foreach ($businessHours as $businessHour) {
            if ($businessHour->open_time <= $businessHour->close_time && $dayOfWeek == $businessHour->day) {
                $diff = Carbon::parse($businessHour->open_time)->diff(Carbon::parse($businessHour->close_time));
                $amount = $amount + ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
            }

            if ($businessHour->active == 1 && $dayOfWeek == $businessHour->day) {
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

            if ($lesson->status == Lesson::STATUS[2]) {
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
                        unset($allTimes[$allTimeKey + 3]);
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

    private function hasStartTimeChanged($request): bool
    {
        return $request->get('start_time') != null;
    }

    private function hasEndTimeChanged($request): bool
    {
        return $request->get('end_time') != null;
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return string|null
     */
    private function interval($startTime, $endTime): ?string
    {
        return Carbon::parse($startTime)->addMinutes($endTime)->format('H:i:s');
    }

    private function scheduleUpdate(Request $request): void
    {
        $lesson = Lesson::query()
            ->where([
                'id' => $request->get('id'),
                'teacher_id' => Auth::id(),
            ])->first();

        $lesson->billing_rate_id = $request->get('billing_rate_id');
        $lesson->color = $request->get('color');

        if ($this->hasStartTimeChanged($request) && $this->hasEndTimeChanged($request)) {
            $duration = $this->interval($request->get('start_time'), $request->get('end_time'));
            $lesson->start_date = $request->get('start_date') . ' ' . $request->get('start_time');
            $lesson->end_date = $request->get('start_date') . ' ' . $duration;
            $lesson->interval = (int)$request->get('end_time');
        }

        if ($this->hasStartTimeChanged($request) && ! $this->hasEndTimeChanged($request)) {
            $duration = $this->interval($request->get('start_time'), $request->get('interval'));
            $lesson->start_date = $request->get('start_date') . ' ' . $request->get('start_time');
            $lesson->end_date = $request->get('start_date') . ' ' . $duration;
        }

        $lesson->notes = $request->get('notes');
        $lesson->status = $request->get('status');
        $lesson->status_updated_at = now();

        $lesson->update();

        if (
            ($this->hasStartTimeChanged($request) && $this->hasEndTimeChanged($request))
            || ($this->hasStartTimeChanged($request) && ! $this->hasEndTimeChanged($request))
            || ($request->get('status') === Lesson::STATUS[1])
            || ($request->get('status') === Lesson::STATUS[2]) // TODO: pass status to email
        ) {
            // email student and parents
            $lessons = collect();
            $lessons[] = $lesson;
            $student = Student::query()->with('getTeacher')->with('parent')->findOrFail($lesson->student->id); // needed for email
            $this->studentLessonService->emailLessonsToStudentParent($student, $lessons, $request->get('status'));
        }
    }

    /**
     * @throws Exception
     */
    private function scheduleUpdateRemaining(Request $request): void
    {
        $getLesson = Lesson::where('id', $request->get('id'))->get();
        $lessonDate = Carbon::parse($request->get('start_date'));
        $endOfMonth = Carbon::parse($lessonDate)->endOfMonth();
        $duration = Carbon::parse($request->get('start_time'))->addMinutes($request->get('end_time'))->format('H:i:s');

        // keep to email parents and students
        // $student = Student::query()->with('getTeacher')->with('parent')->findOrFail($request->get('student_id')); // needed for email

        $lessonsToBeUpdated = Lesson::query()
            ->where('student_id', $request->get('student_id'))
            ->where('teacher_id', Auth::id())
            ->whereBetween('start_date', [$lessonDate, $endOfMonth])
            ->withTrashed()
            ->get();

        $updateTheseLessons = $getLesson->merge($lessonsToBeUpdated);

        $modifyDate = Carbon::parse($request->get('start_date'));

        foreach ($updateTheseLessons as $lesson) {
            $lesson->billing_rate_id = $request->get('billing_rate_id');
            $lesson->color = $request->get('color');
            $lesson->start_date = $modifyDate->format('Y-m-d') . ' ' . $request->get('start_time');
            $lesson->end_date = $modifyDate->format('Y-m-d') . ' ' . $duration;
            $lesson->interval = (int)$request->get('end_time');

            $modifyDate = $modifyDate->modify('+7 day');

            if ($lesson->deleted_at != null) {
                $lesson->restore();
            }

            if ($lesson->end_date > $endOfMonth) {
                $lesson->delete();
            } else {
                $lesson->update();
            }
        }
    }

    /**
     * @param Lesson $lesson
     * @return void
     * @throws Exception
     */
    private function destroyOne(Lesson $lesson): void
    {
        $this->studentLessonService->deleteUnPaidCreatedInvoices($lesson);
        $lesson->delete();
    }

    /**
     * @param Lesson $lesson
     * @return void
     * @throws Exception
     */
    private function destroyAll(Lesson $lesson): void
    {
        $this->studentLessonService->deleteUnPaidCreatedInvoices($lesson);

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
        $this->studentLessonService->deleteUnPaidCreatedInvoices($lesson);

        Lesson::query()->where('student_id', $lesson->student_id)
            ->where('teacher_id', Auth::id())
            ->whereDate('start_date', '>=', $lesson->start_date)
            ->delete();
    }
}
