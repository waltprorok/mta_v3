<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class LessonsScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public Student $student;
    public ?string $status;
    public Teacher $teacher;
    public Collection $lessons;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($student, $lessons, $status = null)
    {
        $this->student = $student;
        $this->status = $status;
        $this->teacher = $student->getTeacher;
        $this->lessons = $lessons;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): LessonsScheduled
    {
        return $this->from($this->teacher->email, $this->teacher->full_name)
            ->subject('New Lesson(s) Scheduled for ' . $this->getLessonMonthName())
            ->markdown('emails.lessons.scheduled');
    }

    private function getLessonMonthName(): string
    {
        $month = $this->lessons->first();
        return date('F Y', strtotime($month->start_date));
    }
}
