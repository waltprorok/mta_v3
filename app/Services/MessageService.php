<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    public function getStudentTeacher()
    {
        $teacher = Student::where('student_id', Auth::id())->firstOrFail(['id', 'student_id', 'teacher_id']);

        return User::whereHas('getTeacher', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->teacher_id);
        })
            ->firstNameAsc()
            ->get(['id', 'first_name', 'last_name', 'created_at', 'teacher', 'student', 'parent']);
    }

    public function getStudentUsers(int $status)
    {
        if ($status == Student::PARENT) {
            return User::whereHas('parentOfStudent', function ($query) {
                $query->where('teacher_id', Auth::id());
            })
                ->firstNameAsc()
                ->get(['id', 'first_name', 'last_name', 'created_at', 'teacher', 'student', 'parent']);
        }

        return User::whereHas('studentUsers', function ($query) use ($status) {
            $query->where('teacher_id', Auth::id())->where('status', $status);
        })
            ->firstNameAsc()
            ->get(['id', 'first_name', 'last_name', 'created_at', 'teacher', 'student', 'parent']);
    }

    private function getParentTeacher(): Collection
    {
        $students = User::with('parentOfStudent:id,student_id,teacher_id,parent_id')
            ->findOrFail(Auth::id());

        return User::whereHas('getTeacher', function ($query) use ($students) {
            $query->where('teacher_id', $students->parentOfStudent->teacher_id);
        })
            ->firstNameAsc()
            ->get(['id', 'first_name', 'last_name', 'created_at', 'teacher', 'student', 'parent']);
    }

    public function getUsers(int $status)
    {
        switch (true) {
            case Auth::user()->teacher:
                return $this->getStudentUsers($status);
            case Auth::user()->student:
                return $this->getStudentTeacher();
            case Auth::user()->parent:
                return $this->getParentTeacher();
            default:
                return null;
        }
    }
}
