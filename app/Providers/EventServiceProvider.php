<?php

namespace App\Providers;

use App\Events\RegisterUserEvent;
use App\Listeners\CreateTeacherListener;
use App\Listeners\SendNewTeacherMessageListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RegisterUserEvent::class => [
            CreateTeacherListener::class,
            SendNewTeacherMessageListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
