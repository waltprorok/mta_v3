<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentListControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $admin;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['admin' => true]);
        $this->user = User::factory()->create(['id' => 3, 'teacher' => true]);
    }

    public function test_admin_student_list_index_view_200()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.student.index'));

        $response->assertOk()
            ->assertViewIs('webapp.admin.student.index');
    }

    public function test_admin_student_list_index_view_url_200()
    {
        $response = $this->actingAs($this->admin)->get('/admin/students');

        $response->assertOk()
            ->assertViewIs('webapp.admin.student.index');
    }

    public function test_student_list_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('student.index'));

        $response->assertOk()
            ->assertViewIs('webapp.student.index');
    }

    public function test_student_list_index_view_url_200()
    {
        $response = $this->actingAs($this->user)->get('/students');

        $response->assertOk()
            ->assertViewIs('webapp.student.index');
    }

    public function test_student_factory()
    {
        Student::factory()->count(5)->create();

        $this->assertDatabaseCount('students', 5);
    }

    public function test_student_list_active_200()
    {
        Student::factory()->create(['teacher_id' => $this->user->id, 'status' => 1]);

        $response = $this->actingAs($this->user)->get('/web/active');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    public function test_student_list_waitlist_200()
    {
        Student::factory()->create(['teacher_id' => $this->user->id, 'status' => 2]);

        $response = $this->actingAs($this->user)->get('/web/waitlist');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    public function test_student_list_lead_200()
    {
        Student::factory()->create(['teacher_id' => $this->user->id, 'status' => 3]);

        $response = $this->actingAs($this->user)->get('/web/leads');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    public function test_student_list_inactive_200()
    {
        Student::factory()->create(['teacher_id' => $this->user->id, 'status' => 4]);

        $response = $this->actingAs($this->user)->get('/web/inactive');

        $response->assertOk();
        $response->assertJsonCount(1);
    }
}
