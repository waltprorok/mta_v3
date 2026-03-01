<?php

namespace App\Http\Controllers;

use App\Models\BusinessHours;
use App\Services\BusinessHoursService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BusinessHourController extends Controller
{
    /**
     * @var BusinessHoursService
     */
    private BusinessHoursService $businessHoursService;

    /**
     * @param BusinessHoursService $businessHoursService
     */
    public function __construct(BusinessHoursService $businessHoursService)
    {
        $this->businessHoursService = $businessHoursService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $hasHours = BusinessHours::query()->where('teacher_id', Auth::id())->exists();

        return $hasHours == null ? $this->create() : $this->show();
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $hours = $this->businessHoursService->getSelectHours();

        return view('webapp.teacher.hours', compact('hours'));
    }

    public function store(Request $request): RedirectResponse
    {
        $teacherId = Auth::id();

        foreach ($request->input('rows', []) as $row) {
            BusinessHours::updateOrCreate(
                [
                    'teacher_id' => $teacherId,
                    'day'        => $row['day'],
                ],
                [
                    'active'     => (int) ($row['active'] ?? 0),
                    'open_time'  => $row['open_time'],
                    'close_time' => $row['close_time'],
                ]
            );
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
            ->get(['id', 'teacher_id', 'day', 'active', 'open_time', 'close_time']);

        $totalHours = $this->businessHoursService->getTotalHours($hours);

        $selectHours = $this->businessHoursService->getSelectHours();

        return view('webapp.teacher.hoursView', compact('hours',  'totalHours', 'selectHours'));
    }

    public function update(Request $request): RedirectResponse
    {
        $teacherId = Auth::id();

        foreach ($request->input('rows', []) as $row) {
            BusinessHours::updateOrCreate(
                [
                    'teacher_id' => $teacherId,
                    'day'        => $row['day'],
                ],
                [
                    'active'     => (int) ($row['active'] ?? 0),
                    'open_time'  => $row['open_time'],
                    'close_time' => $row['close_time'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Business hours updated successfully!');
    }
}
