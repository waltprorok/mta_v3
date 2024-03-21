<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class MarketingTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     * @throws Throwable
     */
    public function test_basic_example()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('You Teach.');
        });
    }
}
