<?php

namespace Tests\Feature\Http\Controllers;

use App\Mail\SupportEmail;
use App\Models\Support;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SupportUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->user = factory(User::class)->create(['teacher' => true, 'student' => false]);
    }

    public function test_support_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('support'));

        $response->assertOk()
            ->assertViewIs('webapp.support.index');
    }

    public function test_support_index_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/support');

        $response->assertOk()
            ->assertViewIs('webapp.support.index');
    }

    public function test_support_create_success()
    {
        $this->actingAs($this->user)->post('/support', [
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'attachment' => null,
        ])->assertRedirect('/support');

        $support = Support::first();

        $this->assertDatabaseCount('supports', 1);

        $this->assertDatabaseHas('supports', [
            'subject' => $support->subject,
            'message' => $support->message,
            'attachment' =>$support->attachment

        ]);

        Mail::assertQueued(SupportEmail::class, function ($mail) use ($support) {
            return $mail->hasTo($support->email);
        });

        Mail::assertQueued(SupportEmail::class, 1);
    }
}
