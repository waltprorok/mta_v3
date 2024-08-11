<?php

namespace App\Http\Controllers;

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
        $lessonsInWeek = $this->lessonsThisWeek() * 30;
        $businessHours = Auth::user()->businessHours->where('active', true);

        foreach ($businessHours as $businessHour) {
            $closeTime = Carbon::createFromFormat('H:i:s', $businessHour->close_time);
            $openTime = Carbon::createFromFormat('H:i:s', $businessHour->open_time);
            $minutesInDay += $closeTime->diffInMinutes($openTime);
        }

        return ($minutesInDay - $lessonsInWeek) / 30 ?? 0;
    }

    private function lessonsThisWeek(): int
    {
        return Auth::user()->lessons->whereBetween('start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    }

    private function getLessonsThisMonth()
    {
        return Auth::user()->lessons->whereBetween('start_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
    }

    private function monthlyIncome(): int
    {
        $lessonsInMonth = $this->getLessonsThisMonth();
        $billingRate = Auth::user()->getTeacherPaymentRate;

        foreach ($billingRate as $rate) {
            if ($rate->type == 'lesson') {
                $monthlyAmount = ($rate->amount * $lessonsInMonth->count());
                break;
            }

            if ($rate->type == 'hourly') {
                $monthlyAmount = ($lessonsInMonth->sum('interval') / 60 * $rate->amount);
                break;
            }

            if ($rate->type == 'monthly') {
                $monthlyAmount = ($rate->amount * $lessonsInMonth->count() / 4);
                break;
            }

            if ($rate->type == 'yearly') {
                $monthlyAmount = ($rate->amount / 52 * $lessonsInMonth->count());
                break;
            }
        }

        return $monthlyAmount ?? 0;
    }
}
