<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Throwable;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
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
