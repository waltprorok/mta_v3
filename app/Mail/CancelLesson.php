<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelLesson extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $lesson;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($lesson)
    {
        $this->lesson = $lesson;
        $this->student = $lesson->student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): CancelLesson
    {
        $emailFrom = $this->student->email ?? $this->student->parent->email;

        $subject = Carbon::parse($this->lesson->start_date)->format('D, M d, Y g:i')
            . ' - ' . Carbon::parse($this->lesson->end_date)->format('g:i a');

        return $this->from($emailFrom, $this->student->full_name)
            ->subject('Cancelled Lesson: ' . $subject)
            ->markdown('emails.lessons.cancelled');
    }
}
