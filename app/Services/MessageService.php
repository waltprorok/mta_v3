<?php

namespace App\Services;

use App\Student;
use App\Teacher;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    private $id = 0;

    private $subject = '';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param int $id
     * @return Student[]|Builder[]|Collection|mixed|object|null
     */
    public function getUsers(int $id)
    {
        switch (true) {
            case Auth::user()->teacher:
                return $this->getStudentUsers($id);
            case Auth::user()->student:
                return $this->getStudentTeacher();
            case Auth::user()->parent:
                return $this->getParentTeacher($id);
            default:
                return null;
        }
    }

    /**
     * @param string $subject
     * @return void
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return Student[]|Builder[]|Collection
     */
    public function getStudentTeacher()
    {
        return Student::with('studentTeacher')->where('student_id', Auth::id())->get();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getStudentUsers(int $id): object
    {
        $this->setId($id);

        if ($this->getId() === 0) {
            $users = User::whereHas('studentUsers', function ($query) {
                $query->where('teacher_id', Auth::id());
            })->firstNameAsc()->get();
        } else {
            $users = User::where('id', $id)->get();
        }

        return $users;
    }

    /**
     * @param string $subject
     * @return string
     */
    public function getSubjectString(string $subject): string
    {
        $this->setSubject($subject);

        if ($this->getSubject() !== '') {
            return $this->subject = 'Re: ' . $subject;
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
            return User::where('id', $this->getId())->get();
        } else {
            $students = User::with('parentOfStudent')->findOrFail(Auth::id());

            foreach ($students->parentOfStudent as $student) {
                $teacherId[] = $student->teacher_id;
            }

            return Teacher::whereIn('teacher_id', $teacherId)->firstNameAsc()->get();
        }
    }
}
