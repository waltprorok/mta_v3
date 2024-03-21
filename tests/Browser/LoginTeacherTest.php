<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Throwable;

class LoginTeacherTest extends DuskTestCase
{
    /**
     * Test logging into app and see the dashboard
     *
     * @return void
     * @throws Throwable
     */
    public function test_login_show_dashboard()
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
