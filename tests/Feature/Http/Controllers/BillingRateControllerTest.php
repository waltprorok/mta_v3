<?php

namespace Feature\Http\Controllers;

use App\Models\BillingRate;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingRateControllerTest extends TestCase
{
    use RefreshDatabase;

    public $admin;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(User::class)->create(['admin' => true, 'student' => false]);
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
    }

    public function test_billing_rate_index_view_200()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('teacher.billing'));

        $response->assertOk()
            ->assertViewIs('webapp.teacher.billing');
    }

    public function test_billing_rate_index_url_200()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/teacher/rates');

        $response->assertOk()
            ->assertViewIs('webapp.teacher.billing');
    }

    public function test_billing_rate_index_web_url_200()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/web/billing-rate');

        $response->assertOk();
    }

    public function test_billing_rate_factory()
    {
        factory(BillingRate::class)->create(['teacher_id' => $this->user->id]);

        $this->assertDatabaseCount('billing_rates', 1);
    }

    public function test_billing_rate_show()
    {
        $billingRate = factory(BillingRate::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/web/billing-rate/' . $billingRate->id);

        $response->assertOk();
        $response->assertJsonCount(13);
    }

    public function test_billing_rate_create_success()
    {
        $this->withoutMiddleware();

        $this->actingAs($this->user)->post('/web/billing-rate', [
            'teacher_id' => $this->user->id,
            'type' => 'monthly',
            'amount' => 100,
            'description' => 'Monthly Rate',
            'default' => 1,
            'flat_rate' => 1,
            'cancelled_twenty_four_hours' => 0,
            'cancelled_forty_eight_hours' => 0,
        ]);

        $this->assertDatabaseCount('billing_rates', 1);
    }

    public function test_billing_rate_update_success()
    {
        $billingRate = factory(BillingRate::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->put('/web/billing-rate/' . $billingRate->id, [
            'type' => 'monthly',
            'amount' => 100,
            'description' => 'Monthly Rate Updated',
            'default' => 1,
            'flat_rate' => 1,
            'cancelled_twenty_four_hours' => 0,
            'cancelled_forty_eight_hours' => 0,
        ]);

        $this->assertDatabaseCount('billing_rates', 1);

        $response->assertOk();
    }

    public function test_billing_rate_delete_success()
    {
        $billingRate = factory(BillingRate::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete('/web/billing-rate/' . $billingRate->id);

        $response->assertOk();

        $this->assertDatabaseCount('billing_rates', 0);
    }
}
