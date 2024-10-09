<?php

namespace App\Services;

use App\Models\Message;
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getStudentTeacher(string $id)
    {
        $message = Message::where('user_id_from', $id)->first();

        return Teacher::where('teacher_id', $message->user_id_from)
            ->get(['id', 'teacher_id', 'first_name', 'last_name', 'email']);
    }

    /**
     * @param int $id
     * @param int $status
     * @return mixed
     */
    public function getStudentUsers(int $id, int $status): object
    {
        if ($status == Student::PARENT) {
            return User::whereHas('parentOfStudent', function ($query) {
                $query->where('teacher_id', Auth::id());
            })
                ->firstNameAsc()
                ->get(['id', 'first_name', 'last_name', 'email']);
        }

        $this->setId($id);

        if ($this->getId() === 0) {
            $users = User::whereHas('studentUsers', function ($query) use ($status) {
                $query->where('teacher_id', Auth::id())->where('status', $status); // pass a status id
            })
                ->firstNameAsc()
                ->get(['id', 'first_name', 'last_name', 'email']);
        } else {
            $users = User::where('id', $id)
                ->get(['id', 'first_name', 'last_name', 'email']);
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
            $students = User::with('parentOfStudents')
                ->findOrFail(Auth::id());

            foreach ($students->parentOfStudents as $student) {
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
                return $this->getStudentTeacher($id);
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
