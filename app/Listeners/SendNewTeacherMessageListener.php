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
     * @param  RegisterUserEvent  $event
     * @return void
     */
    public function handle(RegisterUserEvent $event)
    {
        $user = $event->getUser();

        // create a message for new teacher
        try {
            Message::query()->create([
                'user_id_from' => User::ADMIN_ID,
                'user_id_to' => $user->id,
                'subject' => 'Welcome to Music Teachers Aid',
                'body' => 'There are a couple of things to do before running your studio.<div>
<br><div>In the upper right hand corner click the Profile Icon and Select Settings.<br><div>
<br><div><ol><li>Fill out the&nbsp;<a href="/teacher/studio">Studio Information Page</a>
</li><li>Set your&nbsp;<a href="/teacher/hours">Business Hours</a></li><li>Create at least one&nbsp;
<a href="/teacher/rates">Billing Rate</a></li></ol><div>That\'s it!&nbsp;</div>
<div>Now you can add students and start booking your schedule.</div></div></div></div></div>',
            ]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
