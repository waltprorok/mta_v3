<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('MTA - New User Registration')
            ->greeting('New User Registration')
            ->line('A new teacher has registered for Music Teachers Aid.')
            ->line('Name: '.$this->user->first_name.' '.$this->user->last_name)
            ->line('Email: '.$this->user->email)
            ->line('Registered: '.$this->user->created_at->format('M j, Y g:i A'));
    }
}
