<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class MessageTo extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): MessageTo
    {
        return $this->from($this->fromUserEmail()->email, $this->fromUserEmail()->full_name)
            ->subject($this->request->subject)
            ->markdown('emails.message.to');
    }

    private function fromUserEmail()
    {
        switch (true) {
            case Auth::user()->isTeacher():
                return Auth::user()->getTeacher()->get(['email', 'first_name', 'last_name'])->first();
            case Auth::user()->isStudent():
                return Auth::user()->student()->get(['email', 'first_name', 'last_name'])->first();
            case Auth::user()->isParent():
                return Auth::user();

        }
    }
}
