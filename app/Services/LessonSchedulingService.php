<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class LessonSchedulingService
{
    public function __construct(
        private LessonAvailabilityService $availability,
        private StudentLessonService      $studentLessonService
    ) {}

    public function create(Student $student, array $data): Collection
    {
        $startDate = Carbon::parse($data['start_date'])->startOfDay();
        $endDate = $data['recurrence'] === Lesson::RECURRENCE[0]
            ? $startDate->copy()
            : $startDate->copy()->endOfMonth();
        $duration = (int) $data['end_time'];
        $holidays = Holiday::getTeacherHolidaysForTwoYears()->get();
        $created = collect();

        for ($date = $startDate->copy(); $date->lessThanOrEqualTo($endDate); $date->addWeek()) {
            $start = Carbon::parse($date->toDateString() . ' ' . $data['start_time']);
            $end = $start->copy()->addMinutes($duration);

            if ($this->availability->isAllDayHoliday($date, $holidays)
                || ! $this->availability->withinBusinessHours($student->teacher_id, $start, $end)
                || $this->availability->hasConflict($student->teacher_id, $start, $end)) {
                continue;
            }

            $created->push(Lesson::create([
                'student_id' => $student->id,
                'teacher_id' => $student->teacher_id,
                'billing_rate_id' => $data['billing_rate_id'],
                'title' => $data['title'],
                'color' => $data['color'],
                'start_date' => $start,
                'end_date' => $end,
                'interval' => $duration,
                'recurrence' => $data['recurrence'],
                'status' => Lesson::STATUS[0],
            ]));
        }

        if ($created->isNotEmpty()) {
            $this->studentLessonService->emailLessonsToStudentParent($student, $created);
        }

        return $created;
    }

    public function update(Lesson $lesson, array $data): void
    {
        $originalStart = Carbon::parse($lesson->start_date);
        $originalEnd = Carbon::parse($lesson->end_date);
        $originalStatus = $lesson->status;

        $startDate = $data['start_date'] ?? $originalStart->toDateString();
        $startTime = $data['start_time'] ?? $originalStart->format('H:i:s');
        $duration = (int) ($data['end_time'] ?? $lesson->interval);
        $start = Carbon::parse($startDate . ' ' . $startTime);
        $end = $start->copy()->addMinutes($duration);
        $scheduleChanged = $start->ne($originalStart) || $end->ne($originalEnd);

        if ($scheduleChanged) {
            if (! $this->availability->withinBusinessHours($lesson->teacher_id, $start, $end)) {
                throw new InvalidArgumentException('The selected time is outside business hours.');
            }
            if ($this->availability->isAllDayHoliday($start, Holiday::getTeacherHolidaysForTwoYears()->get())) {
                throw new InvalidArgumentException('The selected date is a holiday.');
            }
            if ($this->availability->hasConflict($lesson->teacher_id, $start, $end, $lesson->id)) {
                throw new InvalidArgumentException('The selected time conflicts with another scheduled lesson.');
            }
            $lesson->start_date = $start;
            $lesson->end_date = $end;
            $lesson->interval = $duration;
        }

        $lesson->billing_rate_id = $data['billing_rate_id'];
        $lesson->color = $data['color'];
        if (array_key_exists('notes', $data)) {
            $lesson->notes = $data['notes'];
        }
        if (array_key_exists('status', $data) && $data['status'] !== null) {
            $lesson->status = $data['status'];
        }
        $statusChanged = $originalStatus !== $lesson->status;
        if ($statusChanged) {
            $lesson->status_updated_at = now();
        }
        $lesson->save();

        if ($scheduleChanged || $statusChanged) {
            $student = Student::query()->with(['getTeacher', 'parent'])
                ->where('teacher_id', $lesson->teacher_id)
                ->findOrFail($lesson->student_id);
            $this->studentLessonService->emailLessonsToStudentParent(
                $student, collect([$lesson]), $lesson->status
            );
        }
    }

    public function delete(Lesson $lesson): void
    {
        $this->studentLessonService->deleteUnPaidCreatedInvoices($lesson);
        $lesson->delete();
    }
}
