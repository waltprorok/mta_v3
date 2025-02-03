<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->make(['admin' => true, 'student' => false]);
    }

    public function test_contact_index_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('contact.index'));

        $response->assertOk()
            ->assertViewIs('webapp.admin.contact.index');
    }

    public function test_admin_contact_index_url_200()
    {
        $response = $this->actingAs($this->user)->get('/admin/contacts');

        $response->assertOk()
            ->assertViewIs('webapp.admin.contact.index');
    }

    public function test_contact_index_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/contacts');

        $response->assertOk();
    }

    public function test_contact_factory()
    {
        Contact::factory()->count(5)->create();

        $this->assertDatabaseCount('contacts', 5);
    }

    public function test_contact_show()
    {
        $contact = Contact::factory()->create();

        $response = $this->actingAs($this->user)->get('/web/contacts/' . $contact->id);

        $response->assertOk();
        $response->assertJsonCount(9);
    }

    public function test_contact_create_success()
    {
        $this->actingAs($this->user)->post('/web/contacts', [
            'name' => 'Test Name',
            'email' => 'test@domain.com',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
        ]);

        $contact = Contact::first();

        $this->assertDatabaseCount('contacts', 1);

        $this->assertDatabaseHas('contacts', [
            'name' => $contact->name,
            'email' => $contact->email,
            'subject' => $contact->subject,
            'message' => $contact->message,
        ]);
    }

    public function test_contact_update_success()
    {
        $contact = Contact::factory()->create();

        $response = $this->actingAs($this->user)->put('/web/contacts/' . $contact->id, [
            'name' => 'Test Name',
            'email' => 'test@domain.com',
            'subject' => 'Test subject line',
            'message' => 'This is a test message.',
        ]);

        $response->assertOk();
    }

    public function test_contact_delete_success()
    {
        $contact = Contact::factory()->create();

        $response = $this->actingAs($this->user)->delete('/web/contacts/' . $contact->id);

        $response->assertOk();

        $this->assertSoftDeleted('contacts', $contact->toArray());
    }
}
