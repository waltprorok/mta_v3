<?php

namespace Feature\Http\Controllers;

use App\Models\Support;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportControllerTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['admin' => true, 'student' => false]);
    }

    public function test_support_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('admin.support.index'));

        $response->assertOk()
            ->assertViewIs('webapp.admin.support.index');
    }

    public function test_admin_contact_index_view_url_200()
    {
        $response = $this->actingAs($this->user)->get('/admin/support');

        $response->assertOk()
            ->assertViewIs('webapp.admin.support.index');
    }

    public function test_contact_index_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/support');

        $response->assertOk();
    }

    public function test_support_factory()
    {
        factory(Support::class, 5)->create();

        $this->assertDatabaseCount('supports', 5);
    }

    public function test_support_show()
    {
        $support = factory(Support::class)->create();

        $response = $this->actingAs($this->user)->get('/web/support/' . $support->id);

        $response->assertOk();
        $response->assertJsonCount(10);
    }

    public function test_support_create_success()
    {
        $this->actingAs($this->user)->post('/web/support', [
            'name' => 'Test Name',
            'email' => 'test@domain.com',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
        ]);

        $this->assertDatabaseCount('supports', 1);
    }

    public function test_support_update_success()
    {
        $support = factory(Support::class)->create();

        $response = $this->actingAs($this->user)->put('/web/support/' . $support->id, [
            'name' => 'Test Name',
            'email' => 'test@domain.com',
            'subject' => 'Test subject line',
            'message' => 'This is a test message.',
        ]);

        $response->assertOk();
    }

    public function test_support_delete_success()
    {
        $support = factory(Support::class)->create();

        $response = $this->actingAs($this->user)->delete('/web/support/' . $support->id);

        $response->assertOk();

        $this->assertSoftDeleted('supports', $support->toArray());
    }
}
