<?php

namespace App\Http\Controllers;

use App\Models\BusinessHours;
use App\Services\BusinessHoursService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BusinessHourController extends Controller
{
    public function __construct(
        private readonly BusinessHoursService $businessHoursService
    ) {}

    public function index(): View
    {
        $hours = BusinessHours::query()
            ->where('teacher_id', Auth::id())
            ->orderBy('day')
            ->get();

        $editing = $hours->isNotEmpty();

        $totalHours = $editing
            ? $this->businessHoursService->getTotalHours($hours)
            : '0:00';

        $selectHours = $this->businessHoursService->getSelectHours();

        return view('webapp.teacher.hours', compact(
            'hours',
            'editing',
            'totalHours',
            'selectHours'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->saveHours($request);

        return back()->with(
            'success',
            'Business hours saved successfully!'
        );
    }

    public function update(Request $request): RedirectResponse
    {
        $this->saveHours($request);

        return back()->with(
            'success',
            'Business hours updated successfully!'
        );
    }

    private function saveHours(Request $request): void
    {
        $validated = $request->validate([
            'rows' => ['required', 'array'],
            'rows.*.day' => ['required', 'integer', 'between:0,6'],
            'rows.*.active' => ['nullable', 'boolean'],
            'rows.*.open_time' => ['required', 'date_format:H:i:s'],
            'rows.*.close_time' => ['required', 'date_format:H:i:s'],
        ]);

        $teacherId = Auth::id();

        foreach ($validated['rows'] as $row) {
            BusinessHours::updateOrCreate(
                [
                    'teacher_id' => $teacherId,
                    'day' => $row['day'],
                ],
                [
                    'active' => (int) ($row['active'] ?? 0),
                    'open_time' => $row['open_time'],
                    'close_time' => $row['close_time'],
                ]
            );
        }
    }
}
