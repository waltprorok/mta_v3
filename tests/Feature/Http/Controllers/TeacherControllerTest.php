<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    public $admin;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(User::class)->create(['admin' => true, 'student' => false]);
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
    }

    public function test_index_page_url_redirect()
    {
        $response = $this->get('/teacher/studio');

        $response->assertStatus(302);
    }

    public function test_index_page_route_redirect()
    {
        $response = $this->get(route('teacher.studioIndex'));

        $response->assertStatus(302);
    }

    public function test_get_teacher_is_null()
    {
        $user = factory(User::class)->create(['teacher' => true, 'student' => false]);

        $this->assertNull($user->getTeacher);
    }

    public function test_get_teacher_is_not_null()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $this->assertNotNull($this->user->getTeacher);
    }

    public function test_admin_teacher_index_view_200()
    {
        $response = $this->actingAs($this->admin)->get(route('teacher.index'));

        $response->assertOk()
            ->assertViewIs('webapp.admin.teacher.index');
    }

    public function test_admin_teacher_index_view_url_200()
    {
        $response = $this->actingAs($this->admin)->get('/admin/teachers');

        $response->assertOk()
            ->assertViewIs('webapp.admin.teacher.index');
    }

    public function test_teacher_settings_store()
    {
        $this->actingAs($this->user)->post('/teacher/store', [
            'teacher_id' => $this->user->id,
            'studio_name' => 'Studio Name',
            'first_name' => 'John',
            'last_name' => 'Snow',
            'address' => '123 Main St',
            'address_2' => 'Apt A',
            'city' => 'Orlando',
            'state' => 'FL',
            'zip' => '34712',
            'email' => 'john_snow@domain.com',
            'phone' => '1234567890'
        ]);

        $this->assertDatabaseCount('teachers', 1);
    }
}
