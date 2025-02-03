<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BusinessHours;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessHourControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
    }

    public function test_index_page_route_redirect()
    {
        $response = $this->get(route('teacher.hours'));

        $response->assertStatus(302);
    }

    public function test_index_page_url_redirect()
    {
        $response = $this->get('/teacher/hours');

        $response->assertStatus(302);
    }

    public function test_index_page_url_view()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/teacher/hours');

        $response->assertOk()
            ->assertViewIs('webapp.teacher.hours');
    }

    public function test_index_page_route_view()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('teacher.hours'));

        $response->assertOk()
            ->assertViewIs('webapp.teacher.hours');
    }

    public function test_business_hours_is_not_null()
    {
        $businessHours = BusinessHours::factory()->count(7)->make();

        $this->assertNotNull($businessHours);
    }

    public function test_business_hours_show_view()
    {
        BusinessHours::factory()->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('teacher.hours'));

        $this->assertDatabaseCount('business_hours', 1);

        $response->assertOk()
            ->assertViewIs('webapp.teacher.hoursView');
    }
}
