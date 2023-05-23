<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * WelcomeEmail constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build(): WelcomeMail
    {
        return $this->subject('Welcome to Music Teachers Aid')
            ->markdown('emails.teacher.welcome');
    }
}
