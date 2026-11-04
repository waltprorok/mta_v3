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
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentLessonController extends Controller
{
    private StudentLessonService $studentLessonService;

    public function __construct(StudentLessonService $studentLessonService)
    {
        $this->studentLessonService = $studentLessonService;
    }

    public function index($id)
    {
        $student = Student::query()->where(['id' => $id, 'teacher_id' => Auth::id()])->firstOrFail();

        return view('webapp.student.schedule', compact('student'));
    }

    public function getStudent(int $id, $day = null): JsonResponse
    {
        $student = Student::query()
            ->with('hasOneLesson')
            ->where(['id' => $id, 'teacher_id' => Auth::id()])
            ->first();
        $studentScheduled = Lesson::query()
            ->where('student_id', $student->id)
            ->where('teacher_id', Auth::id())
            ->where('start_date', '>=', now())
            ->exists();
        $pastLessons = $student->lessons()->orderByDesc('start_date')->limit(4)->get()->reverse()->values();
        $businessHours = BusinessHours::query()
            ->where('teacher_id', Auth::id())
            ->orderBy('day')
            ->get();
        $billingRates = BillingRate::getTeacherActiveRates()->get();
        $holidays = Holiday::getTeacherHolidaysForTwoYears()->get();
        $lessons = Lesson::query()
            ->where('teacher_id', Auth::id())
            ->where('status', '!=', Lesson::STATUS[2])
            ->whereDate('start_date', $day)
            ->orderBy('start_date')
            ->get();

        $startDate = $day;
        $day = is_null($day) ? date('l') : Carbon::parse($day)->format('l');
        $allTimes = $this->getAllTimes($day, $businessHours);
        [, $allTimes] = $this->getTimes(
            $lessons,
            $id,
            $startDate,
            $day,
            $allTimes
        );

        return response()->json([
            'student' => $student,
            'businessHours' => $businessHours,
            'billingRates' => $billingRates,
            'holidays' => $holidays,
            'studentScheduled' => $studentScheduled,
            'allTimes' => $allTimes,
            'lessons' => $pastLessons,
        ]);
    }

    public function show($id)
    {
        $lesson = Lesson::query()
            ->where(['id' => $id, 'teacher_id' => Auth::id()])
            ->firstOrFail();

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
        $teacherId = Auth::id();

        $student = Student::query()
            ->with(['getTeacher', 'parent'])
            ->where('teacher_id', $teacherId)
            ->findOrFail($request->get('student_id'));

        $startDate = Carbon::parse($request->get('start_date'));
        $startTime = $request->get('start_time');
        $duration = (int) $request->get('end_time');
        $recurrence = $request->get('recurrence');

        $endDate = $recurrence === Lesson::RECURRENCE[0]
            ? $startDate->copy()
            : $startDate->copy()->endOfMonth();

        $holidays = Holiday::getTeacherHolidaysForTwoYears()->get();

        $businessHours = BusinessHours::query()
            ->where('teacher_id', $teacherId)
            ->where('active', true)
            ->get();

        $lessons = collect();

        try {
            for (
                $date = $startDate->copy();
                $date->lessThanOrEqualTo($endDate);
                $date->addWeek()
            ) {
                $lessonStart = Carbon::parse($date->format('Y-m-d') . ' ' . $startTime);

                $lessonEnd = $lessonStart->copy()->addMinutes($duration);

                // Skip holidays
                $isHoliday = $holidays->contains(function ($holiday) use ($date) {
                    if (!$holiday->all_day) {
                        return false;
                    }

                    return $date->betweenIncluded(
                        Carbon::parse($holiday->start_date)->startOfDay(),
                        Carbon::parse($holiday->end_date)->endOfDay()
                    );
                });

                if ($isHoliday) {
                    continue;
                }

                // Verify business hours
                $businessHour = $businessHours->firstWhere(
                    'day',
                    $date->dayOfWeek
                );

                if (!$businessHour) {
                    continue;
                }

                $openingTime = Carbon::parse($date->format('Y-m-d') . ' ' . $businessHour->open_time);

                $closingTime = Carbon::parse($date->format('Y-m-d') . ' ' . $businessHour->close_time);

                if (
                    $lessonStart->lessThan($openingTime) ||
                    $lessonEnd->greaterThan($closingTime)
                ) {
                    continue;
                }

                // Check for overlapping lessons
                $hasConflict = Lesson::query()
                    ->where('teacher_id', $teacherId)
                    ->where('start_date', '<', $lessonEnd)
                    ->where('end_date', '>', $lessonStart)
                    ->exists();

                if ($hasConflict) {
                    continue;
                }

                // Create lesson
                $lesson = Lesson::create([
                    'student_id' => $student->id,
                    'teacher_id' => $teacherId,
                    'billing_rate_id' => $request->get('billing_rate_id'),
                    'title' => $request->get('title'),
                    'color' => $request->get('color'),
                    'start_date' => $lessonStart,
                    'end_date' => $lessonEnd,
                    'interval' => $duration,
                    'recurrence' => $recurrence,
                ]);

                $lessons->push($lesson);
            }

            // Email only when lessons were created
            if ($lessons->isNotEmpty()) {
                $this->studentLessonService
                    ->emailLessonsToStudentParent($student, $lessons);
            }

        } catch (Exception $exception) {
            Log::error('Lesson scheduling failed', [
                'teacher_id' => $teacherId,
                'student_id' => $student->id,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unable to schedule lessons.',
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Lessons scheduled successfully.',
            'created' => $lessons->count(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @param ScheduleUpdateRequest $request
     * @return JsonResponse
     */
    public function update(ScheduleUpdateRequest $request): JsonResponse
    {
        try {
            $this->scheduleUpdate($request);
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

        foreach ($businessHours as $businessHour) {

            // Only process active business hours for the selected day
            if (! $businessHour->active || $businessHour->day != $dayOfWeek) {
                continue;
            }

            $openingTime = Carbon::parse($businessHour->open_time);
            $closingTime = Carbon::parse($businessHour->close_time);

            // Ignore invalid business hours
            if ($openingTime->greaterThanOrEqualTo($closingTime)) {
                continue;
            }

            // Generate available times in 15-minute increments
            while ($openingTime->lessThan($closingTime)) {
                $allTimes[] = $openingTime->format('H:i:s');

                $openingTime->addMinutes(15);
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
        $availableTimes = [];

        foreach ($lessons as $lesson) {
            if ($lesson->student_id == $id) {
                $studentScheduled = true;
                break;
            }
        }

        foreach ($allTimes as $time) {
            $available = true;

            $slotStart = Carbon::parse($startDate . ' ' . $time);

            foreach ($lessons as $lesson) {
                $lessonStart = Carbon::parse($lesson->start_date);
                $lessonEnd = Carbon::parse($lesson->end_date);

                // A time slot is unavailable if it falls within an existing lesson.
                // The end time remains available for back-to-back lessons.
                if (
                    $slotStart->greaterThanOrEqualTo($lessonStart) &&
                    $slotStart->lessThan($lessonEnd)
                ) {
                    $available = false;
                    break;
                }
            }

            if ($available) {
                $availableTimes[] = $time;
            }
        }

        return [$studentScheduled, $availableTimes];
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

    /**
     * @throws Exception
     */
    private function scheduleUpdate(Request $request): void
    {
        $lesson = Lesson::query()
            ->where('teacher_id', Auth::id())
            ->findOrFail($request->get('id'));

        // Store original values
        $originalStart = $lesson->start_date;
        $originalEnd = $lesson->end_date;
        $originalStatus = $lesson->status;

        // Update lesson details
        $lesson->billing_rate_id = $request->get('billing_rate_id');
        $lesson->color = $request->get('color');
        $lesson->notes = $request->get('notes');
        $lesson->status = $request->get('status');

        // Determine new date, time, and duration
        $startDate = $request->get('start_date')
            ?: Carbon::parse($lesson->start_date)->format('Y-m-d');

        $startTime = $request->get('start_time')
            ?: Carbon::parse($lesson->start_date)->format('H:i:s');

        $duration = (int) (
        $request->get('end_time')
            ?: $lesson->interval
        );

        $lessonStart = Carbon::parse("{$startDate} {$startTime}");
        $lessonEnd = $lessonStart->copy()->addMinutes($duration);

        // Check whether scheduling changed
        $scheduleChanged =
            $lessonStart->ne(Carbon::parse($originalStart)) ||
            $lessonEnd->ne(Carbon::parse($originalEnd));

        if ($scheduleChanged) {

            // Prevent overlapping lessons
            $hasConflict = Lesson::query()
                ->where('teacher_id', Auth::id())
                ->where('id', '!=', $lesson->id)
                ->where('start_date', '<', $lessonEnd)
                ->where('end_date', '>', $lessonStart)
                ->exists();

            if ($hasConflict) {
                throw new \Exception(
                    'The selected time conflicts with another scheduled lesson.'
                );
            }

            // Update schedule
            $lesson->start_date = $lessonStart;
            $lesson->end_date = $lessonEnd;
            $lesson->interval = $duration;
        }

        // Only update status timestamp when status changes
        $statusChanged = $originalStatus !== $lesson->status;

        if ($statusChanged) {
            $lesson->status_updated_at = now();
        }

        $lesson->save();

        // Notify student/parent when schedule or status changes
        if ($scheduleChanged || $statusChanged) {

            $student = Student::query()
                ->with(['getTeacher', 'parent'])
                ->where('teacher_id', Auth::id())
                ->findOrFail($lesson->student_id);

            $this->studentLessonService->emailLessonsToStudentParent(
                $student,
                collect([$lesson]),
                $lesson->status
            );
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
