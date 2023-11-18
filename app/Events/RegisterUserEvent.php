<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegisterUserEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $attributes;
    private $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $attributes, $user)
    {
        $this->attributes = $attributes;
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
