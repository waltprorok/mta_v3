<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class MessageTo extends Mailable
{
    use Queueable, SerializesModels;

    public Request $request;
    public User $user;

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
        return match (true) {
            Auth::user()->isTeacher() => Auth::user()->getTeacher()->get(['email', 'first_name', 'last_name'])->first(),
            Auth::user()->isStudent() => Auth::user()->student()->get(['email', 'first_name', 'last_name'])->first(),
            Auth::user()->isParent() => Auth::user()
        };
    }
}
