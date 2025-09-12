<?php

namespace App\Mail;

use App\Models\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscribedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Teacher $teacher;

    /**
     * Create a new message instance.
     *
     * @param $teacher
     * @return void
     */
    public function __construct($teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build(): SubscribedMail
    {
        return $this->subject('You have successfully subscribed to Music Teachers Aid')
            ->markdown('emails.teacher.subscribed');
    }
}
