<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(User::class)->create(['admin' => true, 'student' => false]);
    }

    public function test_billing_plans_index_view_200()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.billing.plan.list'));

        $response->assertOk()
            ->assertViewIs('webapp.admin.billing.plan');
    }

    public function test_billing_plans_index_url_view_200()
    {
        $response = $this->actingAs($this->admin)->get('/admin/billing');

        $response->assertOk()
            ->assertViewIs('webapp.admin.billing.plan');
    }

    public function test_billing_plans_index_url_200()
    {
        $response = $this->actingAs($this->admin)->get('/web/billing/plans');

        $response->assertOk();
    }

    public function test_billing_plans_factory()
    {
        factory(Plan::class)->create();

        $this->assertDatabaseCount('plans', 1);
    }
}
