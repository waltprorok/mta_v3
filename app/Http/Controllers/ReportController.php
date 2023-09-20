<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function status(): JsonResponse
    {
        $studentActiveCount = Student::where('teacher_id', Auth::id())
            ->where('status', Student::ACTIVE)
            ->count();

        $studentInActiveCount = Student::where('teacher_id', Auth::id())
            ->where('status', Student::INACTIVE)
            ->count();

        $studentLeadCount = Student::where('teacher_id', Auth::id())
            ->where('status', Student::LEAD)
            ->count();

        $studentWaitlistCount = Student::where('teacher_id', Auth::id())
            ->where('status', Student::WAITLIST)
            ->count();

        return response()
            ->json([$studentActiveCount, $studentInActiveCount, $studentLeadCount, $studentWaitlistCount]);
    }
}
