<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $activeStudentCount = Auth::user()->students->where('status', Student::ACTIVE)->count();
        $monthlyIncome = $this->monthlyIncome();
        $lessonsThisWeek = Auth::user()->lessons->whereBetween('start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $openTimeBlocks = $this->openTimeBlocks();


        return response()->json([
            'activeStudentCount' => $activeStudentCount,
            'monthlyIncome' => $monthlyIncome,
            'lessonsThisWeek' => $lessonsThisWeek,
            'openTimeBlocks' => $openTimeBlocks
        ]);
    }

    private function openTimeBlocks(): int
    {
        $minutesInDay = 0;
        $lessonsInWeek = $this->getLessonsThisWeek() * 30;
        $businessHours = Auth::user()->businessHours->where('active', true);

        foreach ($businessHours as $businessHour) {
            $closeTime = Carbon::createFromFormat('H:i:s', $businessHour->close_time);
            $openTime = Carbon::createFromFormat('H:i:s', $businessHour->open_time);
            $minutesInDay += $closeTime->diffInMinutes($openTime);
        }

        return ($minutesInDay - $lessonsInWeek) / 30 ?? 0;
    }

    private function getLessonsThisWeek(): int
    {
        return Auth::user()
            ->lessons
            ->whereBetween('start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
    }

    private function getLessonsThisMonth()
    {
        return Lesson::with('billingRate:id,type,amount')
            ->whereBetween('start_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->where('teacher_id', Auth::id())
            ->get();
    }

    private function monthlyIncome(): int
    {
        $lessonsInMonth = $this->getLessonsThisMonth();
        $monthlyAmount = 0;

        foreach ($lessonsInMonth as $lesson) {
            if ($lesson->billingRate->type == 'lesson') {
                $monthlyAmount += ($lesson->billingRate->amount);

            }

            if ($lesson->billingRate->type == 'hourly') {
                $monthlyAmount += ($lesson->interval / 60 * $lesson->billingRate->amount);

            }

            if ($lesson->billingRate->type == 'monthly') {
                $monthlyAmount += ($lesson->billingRate->amount / 4);

            }

            if ($lesson->billingRate->type == 'yearly') {
                $monthlyAmount += ($lesson->billingRate->amount / 52);

            }
        }

        return $monthlyAmount ?? 0;
    }
}
