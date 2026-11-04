<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $teacher;
    private Student $studentRecord;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create([
            'student' => true,
        ]);

        $this->teacher = User::factory()->create([
            'teacher' => true,
        ]);

        $this->studentRecord = Student::factory()->create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
        ]);
    }

    public function test_profile_index_view_200(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.profile', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertOk()
            ->assertViewIs('webapp.student.profile')
            ->assertViewHas('student')
            ->assertViewHas('studentJson');
    }

    public function test_profile_index_view_web_200(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get('/students/profile/' . $this->studentRecord->id);

        $response->assertOk()
            ->assertViewIs('webapp.student.profile');
    }

    public function test_profile_show_view_200(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.edit', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertOk()
            ->assertViewIs('webapp.student.edit')
            ->assertViewHas('student')
            ->assertViewHas('instruments');
    }

    public function test_profile_returns_correct_student(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.profile', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertOk()
            ->assertViewHas('student', function ($student) {
                return $student->id === $this->studentRecord->id
                    && $student->teacher_id === $this->teacher->id;
            });
    }

    public function test_edit_returns_correct_student(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('student.edit', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertOk()
            ->assertViewHas('student', function ($student) {
                return $student->id === $this->studentRecord->id
                    && $student->teacher_id === $this->teacher->id;
            });
    }

    public function test_guest_cannot_access_student_profile(): void
    {
        $response = $this->get(route('student.profile', [
            'id' => $this->studentRecord->id,
        ]));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_student_edit(): void
    {
        $response = $this->get(route('student.edit', [
            'id' => $this->studentRecord->id,
        ]));

        $response->assertRedirect(route('login'));
    }

    public function test_teacher_cannot_view_another_teachers_student(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $response = $this->actingAs($anotherTeacher)
            ->get(route('student.profile', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertNotFound();
    }

    public function test_teacher_cannot_edit_another_teachers_student(): void
    {
        $anotherTeacher = User::factory()->create([
            'teacher' => true,
        ]);

        $response = $this->actingAs($anotherTeacher)
            ->get(route('student.edit', [
                'id' => $this->studentRecord->id,
            ]));

        $response->assertNotFound();
    }
}
