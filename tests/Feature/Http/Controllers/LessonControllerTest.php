<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
    }

    public function test_calendar_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('calendar.index'));

        $response->assertOk()
            ->assertViewIs('webapp.calendar.index');
    }
}
