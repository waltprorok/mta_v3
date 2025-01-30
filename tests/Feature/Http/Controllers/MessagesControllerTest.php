<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagesControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
    }

    public function test_messages_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('message.index'));

        $response->assertOk()
            ->assertViewIs('webapp.messages.index');
    }

    public function test_messages_index_view_url_200()
    {
        $response = $this->actingAs($this->user)->get('/messages');

        $response->assertOk()
            ->assertViewIs('webapp.messages.index');
    }

    public function test_messages_inbox_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/messages/inbox');

        $response->assertOk();
    }
}
