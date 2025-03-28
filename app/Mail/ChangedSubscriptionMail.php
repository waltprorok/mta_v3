<?php

namespace App\Mail;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangedSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Plan $plan;

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
    public function build(): ChangedSubscriptionMail
    {
        return $this->subject('Your subscription plan has changed')
            ->markdown('emails.account.plan');
    }
}
