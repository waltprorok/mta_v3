<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Throwable;

class RegisterLoginTest extends DuskTestCase
{
    /**
     * @throws Throwable
     */
    public function testUserCanRegisterCorrectly()
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
                ->assertPathIs('/register');
        });
    }

    /**
     * Test logging into app and see the dashboard
     *
     * @return void
     * @throws Throwable
     */
    public function testLoginShowDashboard()
    {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                ->type('email', 'teacher@domain.com')
                ->type('password', '123456')
                ->press('Login')
                ->assertPathIs('/dashboard');
        });
    }
}
