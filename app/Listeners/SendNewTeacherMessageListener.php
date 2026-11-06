<?php

namespace App\Listeners;

use App\Events\RegisterUserEvent;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class SendNewTeacherMessageListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param RegisterUserEvent $event
     * @return void
     */
    public function handle(RegisterUserEvent $event): void
    {
        $user = $event->getUser();

        $message = <<<'HTML'
        <p>There are a couple of things to do before running your studio.</p>

        <p>In the upper right-hand corner, click the Profile Icon and select Settings.</p>

        <ol>
            <li>Fill out the <a href="/teacher/studio">Studio Information Page</a></li>
            <li>Set your <a href="/teacher/hours">Business Hours</a></li>
            <li>Create at least one <a href="/teacher/rates">Billing Rate</a></li>
            <li><a href="/teacher/holidays">Add Holidays/Time Off</a></li>
            <li><a href="/teacher/settings">Settings (Calendar Default Display)</a></li>
        </ol>

        <p>That's it!</p>

        <p>Now you can add students and start booking your schedule.</p>
    HTML;

        try {
            Message::create([
                'user_id_from' => User::ADMIN_ID,
                'user_id_to' => $user->id,
                'body' => $message,
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to create welcome message for new teacher.', [
                'user_id' => $user->id,
                'exception' => $exception->getMessage(),
            ]);
        }
    }
}
