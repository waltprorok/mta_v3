<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $teacherUser;
    public mixed $parentUser;
    public mixed $studentUser;
    public mixed $student;

    public function setUp(): void
    {
        parent::setUp();
        $this->teacherUser = User::factory()->create(['id' => 1, 'teacher' => true]);
        $this->parentUser = User::factory()->create(['id' => 2, 'parent' => true]);
        $this->studentUser = User::factory()->create(['id' => 3, 'student' => true]);
        $this->student = Student::factory()->create(
            [
                'student_id' => $this->studentUser->id,
                'teacher_id' => $this->teacherUser->id,
                'parent_id' => $this->parentUser->id,
                'first_name' => $this->studentUser->first_name,
                'last_name' => $this->studentUser->last_name,
                'email' => $this->studentUser->email
            ]
        );
    }

    public function test_calendar_index_view_200()
    {
        $response = $this->actingAs($this->parentUser)->get(route('parent.calendar'));

        $response->assertOk()
            ->assertViewIs('webapp.calendar.index');
    }

    public function test_calendar_index_view_url_200()
    {
        $response = $this->actingAs($this->parentUser)->get('/household/calendar');

        $response->assertOk()
            ->assertViewIs('webapp.calendar.index');
    }

    public function test_household_view_200()
    {
        $response = $this->actingAs($this->parentUser)->get(route('parent.household'));

        $response->assertOk()
            ->assertViewIs('webapp.parent.household');
    }

    public function test_household_view_url_200()
    {
        $response = $this->actingAs($this->parentUser)->get('/household/');

        $response->assertOk()
            ->assertViewIs('webapp.parent.household');
    }
}
