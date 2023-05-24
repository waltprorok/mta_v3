<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserEmailChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
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
    public function build(): UserEmailChangedMail
    {
        return $this->subject('Changes have been made to your account')
            ->markdown('emails.account.email');
    }
}
