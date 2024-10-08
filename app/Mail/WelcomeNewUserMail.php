<?php

namespace App\Mail;

use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeNewUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new welcome student email.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): WelcomeNewUserMail
    {
        $teacher = Auth::user()->getTeacher;

        return $this->to($this->user->email)
            ->from($teacher->email, $teacher->full_name)
            ->subject('Welcome to Music Teachers Aid')
            ->markdown('emails.user.welcome');
    }
}
