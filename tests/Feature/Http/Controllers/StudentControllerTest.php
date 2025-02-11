<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->student = User::factory()->create(['student' => true]);
        $this->teacher = User::factory()->create(['teacher' => true]);
    }

    public function test_profile_index_view_200()
    {
        $studentUser = Student::factory()->create(['student_id' => $this->student->id, 'teacher_id' => $this->teacher->id]);
        $response = $this->actingAs($this->teacher)->get(route('student.profile', ['id' => $studentUser->id]));

        $response->assertOk()
            ->assertViewIs('webapp.student.profile');
    }

    public function test_profile_index_view_web_200()
    {
        $studentUser = Student::factory()->create(['student_id' => $this->student->id, 'teacher_id' => $this->teacher->id]);
        $response = $this->actingAs($this->teacher)->get('/students/profile/'. $studentUser->id);

        $response->assertOk()
            ->assertViewIs('webapp.student.profile');
    }

    public function test_profile_show_view_200()
    {
        $studentUser = Student::factory()->create(['student_id' => $this->student->id, 'teacher_id' => $this->teacher->id]);
        $response = $this->actingAs($this->teacher)->get(route('student.edit', ['id' => $studentUser->id]));

        $response->assertOk()
            ->assertViewIs('webapp.student.edit');
    }
}
