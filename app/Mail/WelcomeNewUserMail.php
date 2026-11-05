<?php

namespace App\Mail;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeNewUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Teacher $teacher;

    public function __construct(User $user, Teacher $teacher)
    {
        $this->user = $user;
        $this->teacher = $teacher;
    }

    public function build(): WelcomeNewUserMail
    {
        return $this->to($this->user->email)
            ->from($this->teacher->email, $this->teacher->full_name)
            ->subject('Welcome to Music Teachers Aid')
            ->markdown('emails.user.welcome');
    }
}
