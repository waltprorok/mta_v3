<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
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
        $this->withoutMiddleware();

        $user = factory(User::class)->create(['teacher' => 1]);

        $this->assertNull($user->getTeacher);
    }

    public function test_get_teacher_is_not_null()
    {
        $this->withoutMiddleware();

        $user = factory(User::class)->create(['teacher' => 1]);

        factory(Teacher::class)->create(['teacher_id' => $user->id]);

        $this->assertNotNull($user->getTeacher);
    }

}
