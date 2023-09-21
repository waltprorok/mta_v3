<?php

namespace App\Http\Controllers;

use App\Models\BusinessHours;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BusinessHourController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $hours = BusinessHours::query()->where('teacher_id', Auth::id())->first();

        return $hours == null ? $this->create() : $this->show();
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $hours = $this->getSelectHours();

        return view('webapp.teacher.hours', compact('hours', $hours));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $input = $request->all();

        foreach ($input['rows'] as $index => $value) {
            if (! isset($value['active'])) {
                $active = 0;
            } else {
                $active = $value['active'];
            }

            BusinessHours::query()->create([
                'teacher_id' => Auth::id(),
                'day' => $value['day'],
                'active' => $active,
                'open_time' => $value['open_time'],
                'close_time' => $value['close_time'],
            ]);
        }

        return redirect()->back()->with('success', 'Business hours saved successfully!');
    }

    /**
     * @return Application|Factory|View
     */
    public function show()
    {
        $hours = BusinessHours::query()
            ->where('teacher_id', Auth::id())
            ->orderBy('day')
            ->get();

        $totalHours = $this->getTotalHours($hours);

        $selectHours = $this->getSelectHours();

        return view('webapp.teacher.hoursView', compact('hours', $hours, 'totalHours', $totalHours, 'selectHours', $selectHours));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $input = $request->all();

        foreach ($input['rows'] as $index => $value) {
            if (! isset($value['active'])) {
                $active = 0;
            } else {
                $active = $value['active'];
            }

            $hours = BusinessHours::query()
                ->where(['teacher_id' => Auth::id(), 'day' => $value['day']])
                ->first();
            $hours->teacher_id = Auth::id();
            $hours->day = $value['day'];
            $hours->active = $active;
            $hours->open_time = $value['open_time'];
            $hours->close_time = $value['close_time'];
            $hours->save();
        }

        return redirect()->back()->with('success', 'Business hours updated successfully!');
    }

    /**
     * @return array
     */
    private function getSelectHours(): array
    {
        $startPeriod = Carbon::parse('8:00');
        $endPeriod = Carbon::parse('22:00');

        $period = CarbonPeriod::create($startPeriod, '30 minutes', $endPeriod);

        $hours = [];

        foreach ($period as $date) {
            $hours[] = $date->format('H:i');
        }

        return $hours;
    }

    /**
     * @param $hours
     * @return float|int
     */
    private function getTotalHours($hours)
    {
        $totalHours = 0;

        foreach ($hours as $hour) {
            if ($hour->active) {
                $totalHours += Carbon::createFromTimestamp(strtotime($hour->open_time))->diffInMinutes($hour->close_time);
            }
        }

        return $totalHours / 60;
    }
}
