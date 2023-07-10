<?php

namespace App\Http\Controllers;

use App\Models\BusinessHours;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BusinessHourController extends Controller
{
    /**
     * @return Application|Factory|RedirectResponse|View
     */
    public function index()
    {
        $hours = BusinessHours::where('teacher_id', Auth::id())->first();

        if ($hours == null) {
            return view('webapp.teacher.hours');
        }

        return redirect()->route('teacher.hoursView');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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

            BusinessHours::create([
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
        $hours = BusinessHours::query()->where('teacher_id', Auth::id())->get();

        $totalHours = $this->getTotalsHours($hours);

        return view('webapp.teacher.hoursView', compact('hours', $hours, 'totalHours', $totalHours));
    }

    /**
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        //
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

            $hours = BusinessHours::where('teacher_id', '=', Auth::id())->where('day', '=', $value['day'])->first();
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param object $hours
     * @return float|int
     */
    private function getTotalsHours(object $hours)
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
