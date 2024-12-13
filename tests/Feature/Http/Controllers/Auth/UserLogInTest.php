<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserLogInTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

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

    public function test_new_teacher_user_has_registered()
    {
        config()->set('honeypot.enabled', false);

        factory(User::class)->create(['id' => 2, 'first_name' => 'Admin', 'last_name' => 'User']);

        $user = factory(User::class)->make();

        $response = $this->post('/register', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'teacher' => true,
            'password' => $user->password,
            'password_confirmation' => $user->password,
            'terms' => 1,
        ])->assertStatus(302);

        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);

        Mail::assertQueued(WelcomeMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        Mail::assertQueued(WelcomeMail::class, 1);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = factory(User::class)->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $this->assertGuest();
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
        $this->withoutMiddleware();

        $user = factory(User::class)->create(['teacher' => 1]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_authenticated_student_user_can_access_calendar_student()
    {
        $user = factory(User::class)->create(['student' => 1]);

        $response = $this->actingAs($user)->get('/calendar/student');

        $response->assertStatus(200);
    }

    public function test_authenticated_parent_user_can_access_household()
    {
        $user = factory(User::class)->create(['parent' => 1]);

        $response = $this->actingAs($user)->get('/household');

        $response->assertStatus(200);
    }

    public function test_authenticated_admin_user_can_access_admin_blog()
    {
        $user = factory(User::class)->create(['admin' => 1]);

        $response = $this->actingAs($user)->get('/admin/blog');

        $response->assertStatus(200);
    }
}
