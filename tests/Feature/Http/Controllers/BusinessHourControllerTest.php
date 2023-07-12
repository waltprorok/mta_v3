<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BusinessHours;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessHourControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_index_page_redirect()
    {
        $response = $this->get('/teacher/hours');

        $response->assertStatus(302);
    }

    public function test_index_page_view()
    {
        $user = factory(User::class)->create(['teacher' => 1]);

        $response = $this->actingAs($user)->get('/teacher/hours');

        $response->assertStatus(200);
    }

    public function test_business_hours_is_not_null()
    {
        $businessHours = factory(BusinessHours::class, 7)->make();

        $this->assertNotNull($businessHours);
    }

}
