<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentListController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function adminStudents(): JsonResponse
    {
        $students = Student::query()
            ->orderBy('first_name')
            ->get();

        return response()->json($students);
    }

    public function active(): JsonResponse
    {
        $students = Student::query()
            ->with('hasOneFutureLesson')
            ->where('teacher_id', Auth::id())
            ->where('status', Student::ACTIVE)
            ->firstNameAsc()
            ->get();

        return response()->json($students);
    }

    public function waitlist(): JsonResponse
    {
        $waitlists = Student::query()
            ->where('teacher_id', Auth::id())
            ->where('status', Student::WAITLIST)
            ->firstNameAsc()
            ->get();

        return response()->json($waitlists);
    }

    public function leads(): JsonResponse
    {
        $leads = Student::query()
            ->where('teacher_id', Auth::id())
            ->where('status', Student::LEAD)
            ->firstNameAsc()
            ->get();

        return response()->json($leads);
    }

    public function inactive(): JsonResponse
    {
        $inactives = Student::query()
            ->where('teacher_id', Auth::id())
            ->where('status', Student::INACTIVE)
            ->firstNameAsc()
            ->get();

        return response()->json($inactives);
    }
}
