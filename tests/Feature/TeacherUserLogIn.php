<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests test the way individual
 * units work together and pass massages
 */
class TeacherUserLogIn extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_visitor_can_able_to_login()
    {
        $user = factory(User::class)->create();

        $hasUser = $user ? true : false;

        $this->assertTrue($hasUser);
    }

}
