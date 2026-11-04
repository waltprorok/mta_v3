<?php

namespace App\Services;

use App\Models\BusinessHours;
use App\Models\Holiday;
use App\Models\Lesson;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LessonAvailabilityService
{
    public function availableTimes(int $teacherId, string $date, ?int $excludeLessonId = null): array
    {
        $day = Carbon::parse($date);
        $hours = BusinessHours::query()
            ->where('teacher_id', $teacherId)
            ->where('day', $day->dayOfWeek)
            ->where('active', true)
            ->get();

        $times = [];
        foreach ($hours as $hour) {
            $open = Carbon::parse($day->toDateString() . ' ' . $hour->open_time);
            $close = Carbon::parse($day->toDateString() . ' ' . $hour->close_time);
            if ($open->greaterThanOrEqualTo($close)) {
                continue;
            }
            while ($open->lessThan($close)) {
                $times[] = $open->format('H:i:s');
                $open->addMinutes(15);
            }
        }

        $lessons = $this->activeLessons($teacherId)
            ->whereDate('start_date', $day->toDateString())
            ->when($excludeLessonId !== null, fn ($query) => $query->where('id', '!=', $excludeLessonId))
            ->get();

        return array_values(array_filter(array_unique($times), function ($time) use ($day, $lessons) {
            $start = Carbon::parse($day->toDateString() . ' ' . $time);
            foreach ($lessons as $lesson) {
                if ($start->greaterThanOrEqualTo(Carbon::parse($lesson->start_date))
                    && $start->lessThan(Carbon::parse($lesson->end_date))) {
                    return false;
                }
            }
            return true;
        }));
    }

    public function hasConflict(int $teacherId, Carbon $start, Carbon $end, ?int $excludeLessonId = null): bool
    {
        return $this->activeLessons($teacherId)
            ->when($excludeLessonId !== null, fn ($query) => $query->where('id', '!=', $excludeLessonId))
            ->where('start_date', '<', $end)
            ->where('end_date', '>', $start)
            ->exists();
    }

    public function withinBusinessHours(int $teacherId, Carbon $start, Carbon $end): bool
    {
        $hours = BusinessHours::query()
            ->where('teacher_id', $teacherId)
            ->where('day', $start->dayOfWeek)
            ->where('active', true)
            ->get();

        foreach ($hours as $hour) {
            $open = Carbon::parse($start->toDateString() . ' ' . $hour->open_time);
            $close = Carbon::parse($start->toDateString() . ' ' . $hour->close_time);
            if ($start->greaterThanOrEqualTo($open) && $end->lessThanOrEqualTo($close)) {
                return true;
            }
        }
        return false;
    }

    public function isAllDayHoliday(Carbon $date, Collection $holidays): bool
    {
        return $holidays->contains(function ($holiday) use ($date) {
            return $holiday->all_day && $date->betweenIncluded(
                Carbon::parse($holiday->start_date)->startOfDay(),
                Carbon::parse($holiday->end_date)->endOfDay()
            );
        });
    }

    private function activeLessons(int $teacherId)
    {
        return Lesson::query()
            ->where('teacher_id', $teacherId)
            ->where(function ($query) {
                $query->whereNull('status')->orWhere('status', '!=', Lesson::STATUS[2]);
            });
    }
}
