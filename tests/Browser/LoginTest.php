<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Throwable;

class LoginTest extends DuskTestCase
{
//    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws Throwable
     */
    public function testBasicExample()
    {
//        $user = factory(User::class)->create([
//            'email' => 'teacher9@domain.com',
//        ]);

        $this->browse(function ($browser) {
            $browser->visit('/login')
                ->type('email', 'teacher@domain.com')
                ->type('password', '123456')
                ->press('Login')
                ->assertPathIs('/dashboard');
        });
    }
}
