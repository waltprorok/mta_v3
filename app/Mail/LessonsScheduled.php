<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LessonsScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $status;
    public $teacher;
    public $lessons;

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

    private function getLessonMonthName()
    {
        $month = $this->lessons->first();
        return date('F Y', strtotime($month->start_date));
    }
}
