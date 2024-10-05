<?php

namespace App\Listeners;

use App\Events\RegisterUserEvent;
use App\Mail\WelcomeMail;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Newsletter;

class CreateTeacherListener
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
    public function handle(RegisterUserEvent $event)
    {
        $data = $event->getAttributes();
        $user = $event->getUser();

        // create a new teacher record
        try {
            Teacher::query()->create([
                'teacher_id' => $user->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
            ]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        // email new user
        try {
            Mail::to($data['email'])->send(new WelcomeMail($user));
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());
        }

        if (App::environment('production')) {
            // sign new user up for newsletter
            try {
                Newsletter::subscribePending($data['email']);
            } catch (Exception $exception) {
                Log::warning($exception->getMessage());
            }
        }
    }
}
