<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentListController extends Controller
{
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
            ->select('id', 'teacher_id', 'first_name', 'last_name', 'phone', 'email', 'instrument', 'status')
            ->with('hasOneFutureLesson')
            ->where(['teacher_id' => Auth::id(), 'status' => Student::ACTIVE])
            ->firstNameAsc()
            ->get();

        return response()->json($students);
    }

    public function waitlist(): JsonResponse
    {
        $waitlists = Student::query()
            ->select('id', 'teacher_id', 'first_name', 'last_name', 'phone', 'email', 'instrument', 'status')
            ->where(['teacher_id' => Auth::id(), 'status' => Student::WAITLIST])
            ->firstNameAsc()
            ->get();

        return response()->json($waitlists);
    }

    public function leads(): JsonResponse
    {
        $leads = Student::query()
            ->select('id', 'teacher_id', 'first_name', 'last_name', 'phone', 'email', 'instrument', 'status')
            ->where(['teacher_id' => Auth::id(), 'status' => Student::LEAD])
            ->firstNameAsc()
            ->get();

        return response()->json($leads);
    }

    public function inactive(): JsonResponse
    {
        $inactives = Student::query()
            ->select('id', 'teacher_id', 'first_name', 'last_name', 'phone', 'email', 'instrument', 'status')
            ->where(['teacher_id' => Auth::id(), 'status' => Student::INACTIVE])
            ->firstNameAsc()
            ->get();

        return response()->json($inactives);
    }
}
