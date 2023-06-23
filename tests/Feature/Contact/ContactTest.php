<?php

namespace Tests\Feature\Contact;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_new_contact_submitted_error()
    {
        config()->set('honeypot.enabled', false);

//        $this->mock(NoCaptcha::class, function ($mock) {
//            $mock->shouldReceive('verifyResponse')
//                ->once()
//                ->withArgs(['g-recaptcha-response'])
//                ->andReturn(true);
//        });


//        $this->mock(NoCaptcha::class, function ($mock) {
//            $mock->shouldReceive('display')
//                ->zeroOrMoreTimes()
//                ->andReturn('<input type="hidden" name="g-recaptcha-response" value="1" />');
//        });

        $contact = factory(Contact::class)->make();

        $response = $this->post('/contact', [
            'name' => $contact->name,
            'email' => $contact->email,
            'subject' => $contact->subject,
            'message' => $contact->message,
            'g-recaptcha-response' => '1',
        ]);

        $response->assertSessionHasErrors();

        $response->assertRedirect('/');

        // TODO fix this test to pass successfully with valid data.
//        $this->assertDatabaseHas('contacts', [
//            'email' => $contact->email,
//        ]);
//
//        Mail::assertSent(Contact::class, function ($mail) use ($contact) {
//            return $mail->hasTo($contact->email);
//        });
//
//        Mail::assertSent(ContactForm::class, 1);
    }
}
