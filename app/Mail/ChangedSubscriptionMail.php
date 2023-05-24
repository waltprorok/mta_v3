<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangedSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $plan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $plan)
    {
        $this->user = $user;
        $this->plan = $plan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your subscription plan has changed')
            ->markdown('emails.account.plan');
    }
}
