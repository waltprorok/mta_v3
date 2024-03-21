<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DuskTestCase;
use Throwable;

class RegisterLoginTest extends DuskTestCase
{
    use RefreshDatabase;

    /**
     * @throws Throwable
     */
    public function test_user_can_register_correctly()
    {
        $user = factory(User::class)->create();

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/register')
                ->type('first_name', $user->first_name)
                ->type('last_name', $user->last_name)
                ->type('email', $user->email)
                ->type('password', $user->password)
                ->type('password_confirmation', $user->password)
                ->check('#terms')
                ->press('Register')
                ->assertPathIs('/dashboard');
        });
    }
}
