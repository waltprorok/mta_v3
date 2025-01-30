<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
    }

    public function test_status_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertOk()
            ->assertViewIs('webapp.reports.index');
    }

    public function test_status_url_200()
    {
        $response = $this->actingAs($this->user)->get('/reports/status');

        $response->assertOk()
            ->assertViewIs('webapp.reports.index');
    }

    public function test_status_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/status');

        $response->assertOk();
    }

    public function test_payments_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('reports.payments'));

        $response->assertOk()
            ->assertViewIs('webapp.reports.payments');
    }

    public function test_payments_url_200()
    {
        $response = $this->actingAs($this->user)->get('/reports/payments');

        $response->assertOk()
            ->assertViewIs('webapp.reports.payments');
    }

    public function test_payments_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/payments');

        $response->assertOk();
    }
}
