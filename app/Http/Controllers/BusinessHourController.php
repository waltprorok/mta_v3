<?php

namespace App\Http\Controllers;

use App\Models\BusinessHours;
use App\Services\BusinessHoursService;
use Illuminate\Http\JsonResponse;
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
        return view('webapp.teacher.hours');
    }

    public function getHours(): JsonResponse
    {
        $hours = BusinessHours::query()
            ->where('teacher_id', Auth::id())
            ->orderBy('day')
            ->get();

        return response()->json([
            'hours' => $hours,
            'selectHours' => $this->businessHoursService->getSelectHours(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'day' => ['required', 'integer', 'between:0,6'],
            'active' => ['required', 'boolean'],
            'open_time' => ['required', 'date_format:H:i:s'],
            'close_time' => [
                'required',
                'date_format:H:i:s',
                'after:open_time',
            ],
        ]);

        $hour = BusinessHours::updateOrCreate(
            [
                'teacher_id' => Auth::id(),
                'day' => $validated['day'],
            ],
            [
                'active' => $validated['active'],
                'open_time' => $validated['open_time'],
                'close_time' => $validated['close_time'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Business hours updated successfully!',
            'hour' => $hour,
        ]);
    }
}
