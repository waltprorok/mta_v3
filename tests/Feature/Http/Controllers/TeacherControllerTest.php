<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $admin;

    public mixed $user;

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

    public function test_teacher_settings_create_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('teacher.studioIndex'));

        $response->assertOk()
            ->assertViewIs('webapp.teacher.studioIndex');
    }

    public function test_teacher_settings_create_index_view_url_200()
    {
        $response = $this->actingAs($this->user)->get('/teacher/studio');

        $response->assertOk()
            ->assertViewIs('webapp.teacher.studioIndex');
    }

    public function test_teacher_settings_edit_index_view_200()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('teacher.studioIndex'));

        $response->assertOk()
            ->assertViewIs('webapp.teacher.studiosettings');
    }

    public function test_teacher_settings_edit_index_view_url_200()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/teacher/studio');

        $response->assertOk()
            ->assertViewIs('webapp.teacher.studiosettings');
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

    public function test_teacher_settings_update()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $this->actingAs($this->user)->put('/teacher/update', [
            'teacher_id' => $this->user->id,
            'studio_name' => 'Studio Name Update',
            'first_name' => 'Jane',
            'last_name' => 'Snow',
            'address' => '321 Main St',
            'address_2' => 'Apt B',
            'city' => 'Orlando',
            'state' => 'FL',
            'zip' => '34712',
            'email' => 'jane_snow@domain.com',
            'phone' => '1234567890'
        ]);

        $this->assertDatabaseCount('teachers', 1);
    }

    public function test_teacher_profile_index_view_200()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('teacher.profile'));

        $response->assertOk()
            ->assertViewIs('webapp.teacher.profile');
    }

    public function test_teacher_profile_index_url_200()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/teacher/profile');

        $response->assertOk()
            ->assertViewIs('webapp.teacher.profile');
    }

    public function test_contact_index_web_url_200()
    {
        factory(Teacher::class)->create(['teacher_id' => $this->user->id]);

        $response = $this->actingAs($this->admin)->get('/web/teachers');

        $response->assertOk();
    }
}
