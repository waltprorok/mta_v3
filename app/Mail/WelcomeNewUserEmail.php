<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeNewUserEmail extends Mailable
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
    public function build(): WelcomeNewUserEmail
    {
        return $this->markdown('emails.user.welcome');
    }
}
