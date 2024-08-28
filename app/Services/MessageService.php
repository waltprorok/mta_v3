<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    private $id = 0;

    private $subject = '';

    private $new = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getNewFlag(): bool
    {
        return $this->new;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return Student[]|Builder[]|Collection
     */
    public function getStudentTeacher()
    {
        return Student::with('studentTeacher:id,teacher_id,first_name,last_name,email')
            ->where('student_id', Auth::id())
            ->get();
    }

    /**
     * @param int $id
     * @param int $status
     * @return mixed
     */
    public function getStudentUsers(int $id, int $status): object
    {
        $this->setId($id);

        if ($this->getId() === 0) {
            $users = User::whereHas('studentUsers', function ($query) use ($status) {
                $query->where('teacher_id', Auth::id())->where('status', $status); // pass a status id
            })
                ->firstNameAsc()
                ->get(['student_id', 'first_name', 'last_name', 'email', 'parent_email', 'student', 'teacher', 'parent', 'admin']);
        } else {
            $users = User::where('id', $id)->get([
                'id', 'first_name', 'last_name', 'email', 'student', 'teacher', 'parent', 'admin'
            ]);
        }

        return $users;
    }

    /**
     * @param string $subject
     * @param bool $new
     * @return string
     */
    public function getSubjectString(string $subject, bool $new = false): string
    {
        $this->setSubject($subject);

        if ($new) {
            return $this->subject;
        }

        if ($this->getSubject() !== '') {
            return $this->subject = 'RE: ' . $subject;
        }

        return $this->subject;
    }

    /**
     * @return Teacher[]|Collection
     */
    private function getParentTeacher(int $id): Collection
    {
        $teacherId = [];

        $this->setId($id);

        if ($this->getId() > 0) {
            return User::where('id', $this->getId())
                ->get();
        } else {
            $students = User::with('parentOfStudent:id,parent_id,first_name,last_name,email,teacher')
                ->findOrFail(Auth::id());

            foreach ($students->parentOfStudent as $student) {
                $teacherId[] = $student->teacher_id;
            }

            return Teacher::whereIn('teacher_id', $teacherId)
                ->firstNameAsc()
                ->get(['id', 'teacher_id', 'first_name', 'last_name', 'email']);
        }
    }

    /**
     * @param $id
     * @param int $status
     * @return Student[]|Builder[]|Collection|mixed|object|null
     */
    public function getUsers($id, int $status)
    {
        switch (true) {
            case Auth::user()->teacher:
                return $this->getStudentUsers($id, $status);
            case Auth::user()->student:
                return $this->getStudentTeacher();
            case Auth::user()->parent:
                return $this->getParentTeacher($id);
            default:
                return null;
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $subject
     * @return void
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }
}
