<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    public function getStudentTeacher(): Collection
    {
        $teacher = Student::where('student_id', Auth::id())->firstOrFail(['id', 'student_id', 'teacher_id']);

        return User::whereHas('getTeacher', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->teacher_id);
        })
            ->firstNameAsc()
            ->get(['id', 'first_name', 'last_name', 'created_at', 'teacher', 'student', 'parent']);
    }

    public function getStudentUsers(int $status): Collection
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

    public function getUsers(int $status): ?Collection
    {
        return match (true) {
            Auth::user()->teacher => $this->getStudentUsers($status),
            Auth::user()->student => $this->getStudentTeacher(),
            Auth::user()->parent  => $this->getParentTeacher(),
            Auth::user()->admin   => $this->getActiveTeachers(),
            default => null,
        };
    }

    private function getActiveTeachers(): Collection
    {
        return User::with(['messages' => function ($query) {
            $query->where(['user_id_from' => Auth::id(), 'read' => false])
                ->orderBy('id', 'desc');
        }])
            ->where(['is_active' => true, 'teacher' => true])
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'created_at', 'teacher']);
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
}
