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
        $activeStudentCount = $this->getActiveStudentCount();
        $monthlyIncome = $this->getMonthlyIncome();
        $lessonsThisWeek = $this->getLessonsThisWeek();
        $openTimeBlocks = $this->getOpenTimeBlocks();
        $subscriptionType = $this->getSubscriptionType();
        $subscriptionText = $this->getSubscriptionText();
        $subscriptionMessage = $this->getSubscriptionMessage();

        return response()->json([
            'activeStudentCount' => $activeStudentCount,
            'monthlyIncome' => $monthlyIncome,
            'lessonsThisWeek' => $lessonsThisWeek,
            'openTimeBlocks' => $openTimeBlocks,
            'subscriptionType' => $subscriptionType,
            'subscriptionText' => $subscriptionText,
            'subscriptionMessage' => $subscriptionMessage,
        ]);
    }

    private function getSubscriptionType(): string
    {
        if ($this->isSubscriptionCancelled()) {
            return 'danger';
        }

        if ($this->isSubscriptionTrialExpired()) {
            return 'warn';
        }

        if ($this->isSubscriptionOnFreeTrial()) {
            return 'info';
        }

        return '';
    }

    private function getSubscriptionText(): string
    {
        if ($this->isSubscriptionCancelled()) {
            return 'Your subscription has been cancelled!';
        }

        if ($this->isSubscriptionTrialExpired()) {
            return 'Your free trial has expired!';
        }

        if ($this->isSubscriptionOnFreeTrial()) {
            return 'Enjoy your free trial.';
        }

        return '';
    }

    private function getSubscriptionMessage(): string
    {
        if ($this->isSubscriptionCancelled()) {
            return 'Subscription ended at ' . Auth::user()->subscription('premium')->ends_at->format('m/d/Y');
        }

        if ($this->isSubscriptionTrialExpired()) {
            return '<a href="/account/subscription" style="color:white">Don\'t forget to subscribe.</a>';
        }

        if ($this->isSubscriptionOnFreeTrial()) {
            return '<a href="/account/subscription" style="color:white">Don\'t forget to subscribe.</a>';
        }

        return '';
    }

    public function getCompletedLessonsData()
    {
        $period = Carbon::now()->subMonths(11)->monthsUntil(now());

        $data = [];

        foreach ($period as $date) {
            $data[] = [
                'month' => $date->shortMonthName,
                'year' => $date->year,
                'completed' => Lesson::whereBetween('start_date', [
                    $date->startOfMonth()->format('Y-m-d H:i:s'),
                    $date->endOfMonth()->format('Y-m-d H:i:s')
                ])
                    ->where('complete', 1)
                    ->where('teacher_id', Auth::id())
                    ->count(),
            ];
        }

        return response()->json(['getCompletedLessonsData' => $data]);
    }

    private function getActiveStudentCount()
    {
        return Student::where('status', Student::ACTIVE)
            ->where('teacher_id', Auth::id())
            ->count();
    }

    private function getOpenTimeBlocks(): int
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
        return Lesson::whereBetween('start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('teacher_id', Auth::id())
            ->count();
    }

    private function getLessonsThisMonth()
    {
        return Lesson::with('billingRate:id,type,amount')
            ->whereBetween('start_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->where('teacher_id', Auth::id())
            ->withTrashed()
            ->get();
    }

    private function getMonthlyIncome(): int
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
        }

        return $monthlyAmount ?? 0;
    }

    private function isSubscriptionCancelled(): bool
    {
        return (Auth::user()->subscription('premium') != null && Auth::user()->subscription('premium')->cancelled());
    }

    private function isSubscriptionTrialExpired(): bool
    {
        return (Carbon::now() > Auth::user()->trial_ends_at && ! Auth::user()->subscribed('premium') && ! Auth::user()->admin && ! Auth::user()->parent && ! Auth::user()->student);
    }

    private function isSubscriptionOnFreeTrial(): bool
    {
        return (Carbon::now() < Auth::user()->trial_ends_at && ! Auth::user()->subscribed('premium') && ! Auth::user()->admin);
    }
}
