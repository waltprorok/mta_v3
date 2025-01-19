<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentListControllerTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
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
}
