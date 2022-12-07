<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class StudentListController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function adminStudents(): JsonResponse
    {
        $students = Student::orderBy('first_name', 'asc')->get();

        return response()->json($students, Response::HTTP_OK);
    }

    public function active(): JsonResponse
    {
        $students = Student::with('hasOneLesson')
            ->where('teacher_id', Auth::id())
            ->where('status', Student::ACTIVE)
            ->firstNameAsc()
            ->get();

        return response()->json($students, Response::HTTP_OK);
    }

    public function waitlist(): JsonResponse
    {
        $waitlists = Student::with('teacher')
            ->where('teacher_id', Auth::id())
            ->where('status', Student::WAITLIST)
            ->firstNameAsc()
            ->get();

        return response()->json($waitlists, Response::HTTP_OK);
    }

    public function leads(): JsonResponse
    {
        $leads = Student::with('teacher')
            ->where('teacher_id', Auth::id())
            ->where('status', Student::LEAD)
            ->firstNameAsc()
            ->get();

        return response()->json($leads, Response::HTTP_OK);
    }

    public function inactive(): JsonResponse
    {
        $inactives = Student::with('teacher')
            ->where('teacher_id', Auth::id())
            ->where('status', Student::INACTIVE)
            ->firstNameAsc()
            ->get();

        return response()->json($inactives, Response::HTTP_OK);
    }
}
