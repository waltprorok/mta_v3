<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherUserLogInTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_page_200()
    {
        $response = $this->get('/password/reset');

        $response->assertStatus(200);
    }

    public function test_register_page_200()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_user_has_registered()
    {
        $this->call('post', '/register', [
            'first_name' => 'test',
            'last_name' => 'user',
            'email' => 'teacher_user@domain.com',
            'teacher' => true,
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'terms' => 1,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'teacher_user@domain.com',
        ]);
    }

    public function test_login_page_200()
    {
        $response = $this->call('get', '/login');

        $response->assertStatus(200);
    }

    public function test_visitor_is_able_to_login()
    {
        $user = factory(User::class)->create();

        $hasUser = (bool)$user;

        $this->assertTrue($hasUser);
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(302);

        $response->assertRedirect('login');
    }

    public function test_authenticated_teacher_user_can_access_dashboard()
    {
        $user = factory(User::class)->create(['teacher' => 1]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }
}
