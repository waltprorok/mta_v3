<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['teacher' => true]);
    }

    public function test_dashboard_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk()
            ->assertViewIs('webapp.index');
    }

    public function test_dashboard_index_view_url_200()
    {
        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertOk()
            ->assertViewIs('webapp.index');
    }

    public function test_dashboard_index_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/dashboard');

        $response->assertOk();
    }
}
