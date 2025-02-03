<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['admin' => true, 'student' => false]);
    }

    public function test_user_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('admin.users'));

        $response->assertOk()
            ->assertViewIs('webapp.admin.user.index');
    }

    public function test_admin_user_index_url_200()
    {
        $response = $this->actingAs($this->user)->get('/admin/users');

        $response->assertOk()
            ->assertViewIs('webapp.admin.user.index');
    }

    public function test_user_index_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/users');

        $response->assertOk();
    }

    public function test_user_factory()
    {
        User::factory()->count(5)->create(['teacher' => true, 'student' => true]);

        $this->assertDatabaseCount('users', 6);
    }
}
