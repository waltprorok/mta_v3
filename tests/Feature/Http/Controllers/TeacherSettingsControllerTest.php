<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['teacher' => true, 'student' => false]);
        $this->teacher = Teacher::factory()->create(['teacher_id' => $this->user->id]);
    }

    public function test_teacher_settings_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('teacher.settings'));

        $response->assertOk()
            ->assertViewIs('webapp.teacher.settings');
    }

    public function test_teacher_settings_index_view_url_200()
    {
        $response = $this->actingAs($this->user)->get('/teacher/settings');

        $response->assertOk()
            ->assertViewIs('webapp.teacher.settings');
    }

    public function test_teacher_settings_index_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/teacher-settings');

        $response->assertOk();
    }

    public function test_teacher_settings_create_web_url_200()
    {
        $response = $this->actingAs($this->user)->post('/web/teacher-settings', [
            'teacher_id' => $this->user->id,
            'calendar' => 'month',
            'calendar_min_time' => '14:00:00',
            'calendar_max_time' => '19:00:00',
            'auto_schedule_new_active_students' => true,
        ]);

        $response->assertStatus(201);
    }

    public function test_teacher_settings_update_web_url_200()
    {
        $teacherSettings = TeacherSetting::factory()->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->put('/web/teacher-settings/' . $teacherSettings->id, [
            'teacher_id' => $this->user->id,
            'calendar' => 'agendaWeek',
            'calendar_min_time' => '16:00:00',
            'calendar_max_time' => '20:00:00',
            'auto_schedule_new_active_students' => true,
        ]);

        $response->assertStatus(200);
    }
}
