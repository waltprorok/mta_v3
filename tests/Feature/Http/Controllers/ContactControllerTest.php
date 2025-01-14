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

    public function test_contact_index_route_200()
    {
        $user = factory(User::class)->create(['admin' => 1]);

        $response = $this->actingAs($user)->get('/web/contacts');

        $response->assertOk();
    }

    public function test_contacts_is_not_null()
    {
        $contacts = factory(Contact::class, 5)->make();

        $this->assertNotNull($contacts);
    }
}
