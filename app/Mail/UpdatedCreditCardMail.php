<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdatedCreditCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
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
    public function build(): UpdatedCreditCardMail
    {
        return $this->subject('Your credit card on file was recently updated.')
            ->markdown('emails.account.card');
    }
}
