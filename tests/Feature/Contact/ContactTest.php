<?php

namespace Tests\Feature\Contact;


use Anhskohbo\NoCaptcha\NoCaptcha;
use App\Mail\ContactForm;
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

    public function test_new_contact_submitted()
    {
        config()->set('honeypot.enabled', false);

        $mockNoCaptcha = $this->createMock(NoCaptcha::class);

        $mockNoCaptcha->method('verifyResponse')
            ->willReturn(true);

        $mockRecaptchaResponse = $this->mock(NoCaptcha::class, function ($mock) {
            $mock->shouldReceive('display')
                ->zeroOrMoreTimes()
                ->andReturn('<input type="hidden" name="g-recaptcha-response" value="1" />');
        });

        self::assertTrue($mockNoCaptcha->verifyResponse($mockRecaptchaResponse));

        $contact = factory(Contact::class)->make();

        $response = $this->post('/contact', [
            'name' => $contact->name,
            'email' => $contact->email,
            'subject' => $contact->subject,
            'message' => $contact->message,
            'g-recaptcha-response' => '1',
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect('/contact');

        $this->assertDatabaseHas('contacts', [
            'email' => $contact->email,
        ]);

        Mail::assertSent(ContactForm::class, function ($mail) use ($contact) {
            return $mail->hasTo('waltprorok@gmail.com');
        });

        Mail::assertSent(ContactForm::class, 1);
    }
}
