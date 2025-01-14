<?php

namespace Feature\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_contact_index_view_200()
    {
        $user = factory(User::class)->create(['admin' => 1]);

        $response = $this->actingAs($user)->get(route('contact.index'));

        $response->assertOk()
            ->assertViewIs('webapp.admin.contact.index');
    }

    public function test_contact_index_url_200()
    {
        $user = factory(User::class)->create(['admin' => 1]);

        $response = $this->actingAs($user)->get('/web/contacts');

        $response->assertOk();
    }

    public function test_contact_factory()
    {
        factory(Contact::class, 5)->create();

        $this->assertDatabaseCount('contacts', 5);
    }

    public function test_contact_show()
    {
        $user = factory(User::class)->create(['admin' => 1]);

        $contact = factory(Contact::class)->create();

        $response = $this->actingAs($user)->get('/web/contacts/' . $contact->id);

        $response->assertOk();
        $response->assertJsonCount(9);
    }

    public function test_contact_create_success()
    {
        $user = factory(User::class)->create(['admin' => 1]);

        $this->actingAs($user)->post('/web/contacts', [
            'name' => 'Test Name',
            'email' => 'test@domain.com',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
        ]);

        $this->assertDatabaseCount('contacts', 1);
    }

    public function test_contact_update_success()
    {
        $user = factory(User::class)->create(['admin' => 1]);

        $contact = factory(Contact::class)->create();

        $response = $this->actingAs($user)->put('/web/contacts/' . $contact->id, [
            'name' => 'Test Name',
            'email' => 'test@domain.com',
            'subject' => 'Test subject line',
            'message' => 'This is a test message.',
        ]);

        $response->assertOk();
    }

    public function test_contact_delete_success()
    {
        $user = factory(User::class)->create(['admin' => 1]);

        $contact = factory(Contact::class)->create();

        $response = $this->actingAs($user)->delete('/web/contacts/' . $contact->id);

        $response->assertOk();

        $this->assertSoftDeleted('contacts', $contact->toArray());
    }
}
