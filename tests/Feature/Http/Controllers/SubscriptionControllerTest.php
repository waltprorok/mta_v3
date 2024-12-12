<?php

namespace Tests\Feature\Http\Controllers;

use App\Mail\CancelledSubscriptionMail;
use App\Mail\ChangedSubscriptionMail;
use App\Mail\ResumeSubscriptionMail;
use App\Mail\SubscribedMail;
use App\Mail\UpdatedCreditCardMail;
use App\Mail\UserEmailChangedMail;
use App\Mail\UserPasswordUpdatedMail;
use App\Mail\WelcomeNewUserMail;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubscriptionControllerTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->user = factory(User::class)->make();
    }

    public function test_cancel_subscription_email()
    {
        Mail::to($this->user->email)->queue(new CancelledSubscriptionMail($this->user));

        Mail::assertQueued(CancelledSubscriptionMail::class, 1);
    }

    public function test_change_subscription_email()
    {
        $newPlan = factory(Plan::class)->make();

        Mail::to($this->user->email)->queue(new ChangedSubscriptionMail($this->user, $newPlan));

        Mail::assertQueued(ChangedSubscriptionMail::class, 1);
    }

    public function test_resume_subscription_email()
    {
        Mail::to($this->user->email)->queue(new ResumeSubscriptionMail($this->user));

        Mail::assertQueued(ResumeSubscriptionMail::class, 1);
    }

    public function test_subscribed_email()
    {
        Mail::to($this->user->email)->queue(new SubscribedMail($this->user));

        Mail::assertQueued(SubscribedMail::class, 1);
    }

    public function test_update_user_email()
    {
        $this->actingAs($this->user);

        $this->post('/account/update-profile', [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'timezone' => $this->user->getTimezone(),
        ])->assertStatus(302);

        Mail::to($this->user->email)->queue(new UserEmailChangedMail($this->user));

        Mail::assertQueued(UserEmailChangedMail::class, 1);
    }

    public function test_update_credit_card_email()
    {
        Mail::to($this->user->email)->queue(new UpdatedCreditCardMail($this->user));

        Mail::assertQueued(UpdatedCreditCardMail::class, 1);
    }

    public function test_update_password_email()
    {
        Mail::to($this->user->email)->queue(new UserPasswordUpdatedMail($this->user));

        Mail::assertQueued(UserPasswordUpdatedMail::class, 1);
    }

    public function test_welcome_new_user_email()
    {
        Mail::to($this->user->email)->queue(new WelcomeNewUserMail($this->user));

        Mail::assertQueued(WelcomeNewUserMail::class, 1);
    }
}
