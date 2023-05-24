<?php

namespace Tests\Feature\Subscription;

use App\Mail\UserEmailChangedMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /**
     * @test
     * @return void
     */
    public function test_update_user_email()
    {
        $user = factory(User::class)->make();

        $this->actingAs($user);

        $this->post('/account/updateProfile', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ])->assertStatus(302);

        Mail::to($user->email)->send(new UserEmailChangedMail($user));

        Mail::assertSent(UserEmailChangedMail::class, 1);
    }
}
