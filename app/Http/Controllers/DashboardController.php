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
        $todayIncome = $this->getTodayIncome();
        $weeklyIncome = $this->getweeklyIncome();
        $monthlyIncome = $this->getMonthlyIncome();
        $yearlyIncome = $this->getYearlyIncome();
        $lessonsThisWeek = $this->getLessonsThisWeekCount();
        $cancelledLessonsThisWeek = $this->getCancelledLessonsThisWeekCount();
        $openTimeBlocks = $this->getOpenTimeBlocks();
        $subscriptionType = $this->getSubscriptionType();
        $subscriptionText = $this->getSubscriptionText();
        $subscriptionMessage = $this->getSubscriptionMessage();

        return response()->json([
            'activeStudentCount' => $activeStudentCount,
            'todayIncome' => $todayIncome,
            'weeklyIncome' => $weeklyIncome,
            'monthlyIncome' => $monthlyIncome,
            'yearlyIncome' => $yearlyIncome,
            'lessonsThisWeek' => $lessonsThisWeek,
            'cancelledLessonsThisWeek' => $cancelledLessonsThisWeek,
            'openTimeBlocks' => $openTimeBlocks,
            'subscriptionType' => $subscriptionType,
            'subscriptionText' => $subscriptionText,
            'subscriptionMessage' => $subscriptionMessage,
        ]);
    }

    private function getSubscriptionType(): string
    {
        if ($this->isSubscriptionCancelled()) {
            return 'error';
        }

        if ($this->isSubscriptionTrialExpired()) {
            return 'warn';
        }

        if ($this->isSubscriptionOnFreeTrial()) {
            return 'info';
        }

        if ($this->isSubscribed()) {
            return 'success';
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

        if ($this->isSubscribed()) {
            return 'Enjoy your subscription.';
        }

        return '';
    }

    private function getSubscriptionMessage(): string
    {
        if ($this->isSubscriptionCancelled()) {
            return 'Subscription ends at ' . Auth::user()->subscription('premium')->ends_at->format('m/d/Y');
        }

        if ($this->isSubscriptionTrialExpired()) {
            return '<a href="/account/subscription" style="color:white">Don\'t forget to subscribe.</a>';
        }

        if ($this->isSubscriptionOnFreeTrial()) {
            return '<a href="/account/subscription" style="color:white">Don\'t forget to subscribe.</a>';
        }

        if ($this->isSubscribed()) {
            return 'Thank you for subscribing!';
        }

        return '';
    }

    public function getCompletedLessonsData()
    {
        $period = now()->subMonths(11)->monthsUntil(now());
        $data = [];

        foreach ($period as $date) {
            $data[] = [
                'month' => $date->shortMonthName,
                'year' => $date->year,
                'completed' => Lesson::whereBetween('start_date', [
                    $date->startOfMonth()->format('Y-m-d H:i:s'),
                    $date->endOfMonth()->format('Y-m-d H:i:s')
                ])
                    ->where('complete', true)
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
        $lessonsInWeek = $this->getLessonsThisWeekCount() * 30;
        $businessHours = Auth::user()->getBusinessHours->where('active', true);

        foreach ($businessHours as $businessHour) {
            $closeTime = Carbon::createFromFormat('H:i:s', $businessHour->close_time);
            $openTime = Carbon::createFromFormat('H:i:s', $businessHour->open_time);
            $minutesInDay += $closeTime->diffInMinutes($openTime);
        }

        return ($minutesInDay - $lessonsInWeek) / 30 ?? 0;
    }

    private function getLessonsThisWeekCount(): int
    {
        return Lesson::whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', '!=', Lesson::STATUS[2])
            ->where('teacher_id', Auth::id())
            ->count();
    }

    private function getCancelledLessonsThisWeekCount(): int
    {
        return Lesson::whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', Lesson::STATUS[2])
            ->where('teacher_id', Auth::id())
            ->count();
    }

    private function getLessonsToday()
    {
        return Lesson::with('billingRate:id,type,amount')
            ->whereBetween('start_date', [now()->startOfDay(), now()->endOfDay()])
            ->where('teacher_id', Auth::id())
            ->withTrashed()
            ->get();
    }

    private function getLessonsThisWeekly()
    {
        return Lesson::with('billingRate:id,type,amount')
            ->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('teacher_id', Auth::id())
            ->withTrashed()
            ->get();
    }

    private function getLessonsThisMonth()
    {
        return Lesson::with('billingRate:id,type,amount')
            ->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('teacher_id', Auth::id())
            ->withTrashed()
            ->get();
    }

    private function getLessonsThisYearly()
    {
        return Lesson::with('billingRate:id,type,amount')
            ->whereBetween('start_date', [now()->startOfYear(), now()->endOfYear()])
            ->where('teacher_id', Auth::id())
            ->withTrashed()
            ->get();
    }

    private function getTodayIncome(): int
    {
        $lessonsToday = $this->getLessonsToday();
        return $this->calculateLessonTotals($lessonsToday);
    }

    private function getWeeklyIncome(): int
    {
        $lessonsInWeek = $this->getLessonsThisWeekly();
        return $this->calculateLessonTotals($lessonsInWeek);
    }

    private function getMonthlyIncome(): int
    {
        $lessonsInMonth = $this->getLessonsThisMonth();
        return $this->calculateLessonTotals($lessonsInMonth);
    }

    private function getYearlyIncome(): int
    {
        $lessonsInYear = $this->getLessonsThisYearly();
        return $this->calculateLessonTotals($lessonsInYear);
    }

    private function isSubscriptionCancelled(): bool
    {
        return (Auth::user()->subscription('premium') != null && Auth::user()->subscription('premium')->cancelled());
    }

    private function isSubscriptionTrialExpired(): bool
    {
        return (now() > Auth::user()->trial_ends_at && ! Auth::user()->subscribed('premium') && ! Auth::user()->admin && ! Auth::user()->parent && ! Auth::user()->student);
    }

    private function isSubscriptionOnFreeTrial(): bool
    {
        return (now() < Auth::user()->trial_ends_at && ! Auth::user()->subscribed('premium') && ! Auth::user()->admin);
    }

    private function isSubscribed(): bool
    {
        return Auth::user()->subscribed('premium');
    }

    /**
     * @param $lessonsInWeek
     * @return float|int
     */
    private function calculateLessonTotals($lessonsInWeek)
    {
        $amount = 0;

        foreach ($lessonsInWeek as $lesson) {
            if ($lesson->billingRate->type == 'lesson') {
                $amount += ($lesson->billingRate->amount);
            }

            if ($lesson->billingRate->type == 'hourly') {
                $amount += ($lesson->interval / 60 * $lesson->billingRate->amount);
            }

            if ($lesson->billingRate->type == 'monthly') {
                $amount += ($lesson->billingRate->amount / 4);
            }
        }

        return $amount ?? 0;
    }
}
