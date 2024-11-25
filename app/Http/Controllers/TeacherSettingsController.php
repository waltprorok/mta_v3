<?php

namespace App\Http\Controllers;

use App\Models\TeacherSetting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeacherSettingsController extends Controller
{
    public function index()
    {
        try {
            $billingRate = TeacherSetting::query()
                ->where('teacher_id', Auth::id())
                ->first();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($billingRate);
    }

    public function store(Request $request): JsonResponse
    {
        
        try {
            TeacherSetting::query()->create([
                'teacher_id' => Auth::id(),
                'calendar' => $request->get('calendar') ?? 'Month',
                'auto_schedule_new_active_students' => $request->get('auto_schedule_new_active_students') ?? false,

            ]);

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function update(Request $request, TeacherSetting $teacherSetting): JsonResponse
    {
        try {
            $teacherSetting->update($request->all());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json();
    }
}
