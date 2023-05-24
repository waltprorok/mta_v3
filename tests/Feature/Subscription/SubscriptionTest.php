<?php

namespace Tests\Feature\Subscription;

use App\Mail\CancelledSubscriptionMail;
use App\Mail\ChangedSubscriptionMail;
use App\Mail\ResumeSubscriptionMail;
use App\Mail\SubscribedMail;
use App\Mail\UpdatedCreditCardMail;
use App\Mail\UserEmailChangedMail;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubscriptionTest extends TestCase
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
        Mail::to($this->user->email)->send(new CancelledSubscriptionMail($this->user));

        Mail::assertSent(CancelledSubscriptionMail::class, 1);
    }

    public function test_change_subscription_email()
    {
        $newPlan = factory(Plan::class)->make();

        Mail::to($this->user->email)->send(new ChangedSubscriptionMail($this->user, $newPlan));

        Mail::assertSent(ChangedSubscriptionMail::class, 1);
    }

    public function test_resume_subscription_email()
    {
        Mail::to($this->user->email)->send(new ResumeSubscriptionMail($this->user));

        Mail::assertSent(ResumeSubscriptionMail::class, 1);
    }

    public function test_subscribed_email()
    {
        Mail::to($this->user->email)->send(new SubscribedMail($this->user));

        Mail::assertSent(SubscribedMail::class, 1);
    }

    public function test_update_user_email()
    {
        $this->actingAs($this->user);

        $this->post('/account/updateProfile', [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
        ])->assertStatus(302);

        Mail::to($this->user->email)->send(new UserEmailChangedMail($this->user));

        Mail::assertSent(UserEmailChangedMail::class, 1);
    }

    public function test_update_credit_card_email()
    {
        Mail::to($this->user->email)->send(new UpdatedCreditCardMail($this->user));

        Mail::assertSent(UpdatedCreditCardMail::class, 1);
    }
}
