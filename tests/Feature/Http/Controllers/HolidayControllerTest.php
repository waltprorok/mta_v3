<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Holiday;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HolidayControllerTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
    }

    public function test_holiday_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('teacher.holidays'));

        $response->assertOk()
            ->assertViewIs('webapp.teacher.holiday');
    }

    public function test_holiday_index_url_200()
    {
        $response = $this->actingAs($this->user)->get('/teacher/holidays');

        $response->assertOk()
            ->assertViewIs('webapp.teacher.holiday');
    }

    public function test_holiday_index_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/holiday');

        $response->assertOk();
    }

    public function test_holiday_factory()
    {
        factory(Holiday::class, 1)->create();

        $this->assertDatabaseCount('holidays', 1);
    }

    public function test_holiday_show()
    {
        $holiday = factory(Holiday::class)->create();

        $response = $this->actingAs($this->user)->get('/web/holiday/' . $holiday->id);

        $response->assertOk();
        $response->assertJsonCount(9);
    }

    public function test_holiday_create_success()
    {
        $this->actingAs($this->user)->post('/web/holiday', [
            'teacher_id' => $this->user->id,
            'title' => 'Christmas Eve',
            'color' => '#5499C7',
            'start_date' => '2025-12-24 00:00:01',
            'end_date' => '2025-12-24 11:59:59',
            'all_day' => true,
        ]);

        $holiday = Holiday::first();

        $this->assertDatabaseCount('holidays', 1);

        $this->assertDatabaseHas('holidays', [
            'title' => $holiday->title,
            'color' => $holiday->color,
            'start_date' => $holiday->start_date,
            'end_date' => $holiday->end_date,
            'all_day' => $holiday->all_day
        ]);
    }

    public function test_holiday_update_success()
    {
        $holiday = factory(Holiday::class)->create();

        $response = $this->actingAs($this->user)->put('/web/holiday/' . $holiday->id, [
            'title' => 'New Years Eve',
            'color' => '#85929E',
            'start_date' => '2025-12-31 00:00:01',
            'end_date' => '2025-12-31 11:59:59',
            'all_day' => true,
        ]);

        $response->assertOk();
    }

    public function test_holiday_delete_success()
    {
        $holiday = factory(Holiday::class)->create();

        $response = $this->actingAs($this->user)->delete('/web/holiday/' . $holiday->id);

        $response->assertOk();

        $this->assertDatabaseCount('holidays', 0);
    }
}
