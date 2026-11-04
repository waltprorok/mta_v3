<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleUpdateRequest;
use App\Http\Requests\StoreScheduleApptRequest;
use App\Models\BillingRate;
use App\Models\BusinessHours;
use App\Models\Holiday;
use App\Models\Lesson;
use App\Models\Student;
use App\Services\LessonAvailabilityService;
use App\Services\LessonSchedulingService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentLessonController extends Controller
{
    public function __construct(
        private readonly LessonAvailabilityService $availability,
        private readonly LessonSchedulingService $scheduling
    ) {}

    public function index($id)
    {
        $student = Student::query()
            ->where(['id' => $id, 'teacher_id' => Auth::id()])
            ->firstOrFail();

        return view('webapp.student.schedule', compact('student'));
    }

    public function getStudent(int $id, $day = null): JsonResponse
    {
        $student = Student::query()->with('hasOneLesson')
            ->where(['id' => $id, 'teacher_id' => Auth::id()])
            ->firstOrFail();

        $studentScheduled = Lesson::query()
            ->where('student_id', $student->id)
            ->where('teacher_id', Auth::id())
            ->where(function ($query) {
                $query->whereNull('status')->orWhere('status', '!=', Lesson::STATUS[2]);
            })
            ->where('start_date', '>=', now())
            ->exists();

        $pastLessons = $student->lessons()->orderByDesc('start_date')
            ->limit(4)->get()->reverse()->values();
        $businessHours = BusinessHours::query()->where('teacher_id', Auth::id())
            ->orderBy('day')->get();
        $billingRates = BillingRate::getTeacherActiveRates()->get();
        $holidays = Holiday::getTeacherHolidaysForTwoYears()->get();
        $selectedDate = $day ? Carbon::parse($day)->toDateString() : now()->toDateString();

        return response()->json([
            'student' => $student,
            'businessHours' => $businessHours,
            'billingRates' => $billingRates,
            'holidays' => $holidays,
            'studentScheduled' => $studentScheduled,
            'allTimes' => $this->availability->availableTimes(Auth::id(), $selectedDate),
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

    public function getLessonReschedule(int $id, $day = null): JsonResponse
    {
        $lesson = Lesson::query()->with(['student', 'billingRate'])
            ->where('teacher_id', Auth::id())->findOrFail($id);
        $businessHours = BusinessHours::query()->where('teacher_id', Auth::id())->get();
        $billingRates = BillingRate::getTeacherActiveRates()->get();
        $holidays = Holiday::getTeacherHolidaysForTwoYears()->get();
        $selectedDate = $day ? Carbon::parse($day)->toDateString()
            : Carbon::parse($lesson->start_date)->toDateString();

        return response()->json([
            'lesson' => $lesson,
            'allTimes' => $this->availability->availableTimes(Auth::id(), $selectedDate, $lesson->id),
            'startDate' => $day, // Preserve existing response contract.
            'businessHours' => $businessHours,
            'holidays' => $holidays,
            'billingRates' => $billingRates,
        ]);
    }

    public function store(StoreScheduleApptRequest $request): JsonResponse
    {
        $student = Student::query()->with(['getTeacher', 'parent'])
            ->where('teacher_id', Auth::id())
            ->findOrFail($request->get('student_id'));

        try {
            $lessons = $this->scheduling->create($student, $request->validated());
        } catch (Exception $exception) {
            Log::error('Lesson scheduling failed', [
                'teacher_id' => Auth::id(),
                'student_id' => $student->id,
                'message' => $exception->getMessage(),
            ]);
            return response()->json(['message' => 'Unable to schedule lessons.'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Lessons scheduled successfully.',
            'created' => $lessons->count(),
        ], Response::HTTP_CREATED);
    }

    public function update(ScheduleUpdateRequest $request): JsonResponse
    {
        $lesson = Lesson::query()->where('teacher_id', Auth::id())
            ->findOrFail($request->get('id'));

        try {
            $this->scheduling->update($lesson, $request->validated());
        } catch (Exception $exception) {
            Log::error('Lesson update failed', [
                'lesson_id' => $lesson->id,
                'message' => $exception->getMessage(),
            ]);
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_OK);
    }

    public function destroy(Lesson $id): JsonResponse
    {
        // Route model binding does not automatically restrict lessons to the logged-in teacher.
        abort_unless((int) $id->teacher_id === (int) Auth::id(), Response::HTTP_NOT_FOUND);

        try {
            $this->scheduling->delete($id);
        } catch (Exception $exception) {
            Log::error('Lesson deletion failed', [
                'lesson_id' => $id->id,
                'message' => $exception->getMessage(),
            ]);
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_OK);
    }
}
