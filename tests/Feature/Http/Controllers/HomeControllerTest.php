<?php

namespace Tests\Feature\Http\Controllers;

use Anhskohbo\NoCaptcha\NoCaptcha;
use App\Mail\ContactForm;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_home_page_url_200()
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_home_page_route_200()
    {
        $response = $this->get(route('home'));

        $response->assertOk();
    }

    public function test_faq_page_url_200()
    {
        $response = $this->get('/faq');

        $response->assertOk();
    }

    public function test_faq_page_route_200()
    {
        $response = $this->get(route('faq'));

        $response->assertOk();
    }

    public function test_contact_page_url_200()
    {
        $response = $this->get('/contact');

        $response->assertOk();
    }

    public function test_contact_page_route_200()
    {
        $response = $this->get(route('contact'));

        $response->assertOk();
    }

    public function test_blog_page_url_200()
    {
        $response = $this->get('/blog');

        $response->assertOk();
    }

    public function test_blog_page_route_200()
    {
        $response = $this->get(route('blog.index'));

        $response->assertOk();
    }

    public function test_blog_scheduling_students_page_200()
    {
        $this->seed();

        $response = $this->get('/blog/scheduling-students');

        $response->assertOk();
    }

    public function test_pricing_page_url_200()
    {
        $response = $this->get('/pricing');

        $response->assertOk();
    }

    public function test_pricing_page_route_200()
    {
        $response = $this->get(route('pricing'));

        $response->assertOk();
    }

    public function test_terms_page_url_200()
    {
        $response = $this->get('/terms');

        $response->assertOk();
    }

    public function test_terms_page_route_200()
    {
        $response = $this->get(route('terms'));

        $response->assertOk();
    }

    public function test_privacy_page_url_200()
    {
        $response = $this->get('/privacy');

        $response->assertOk();
    }

    public function test_privacy_page_route_200()
    {
        $response = $this->get(route('privacy'));

        $response->assertOk();
    }

    /**
     * @return void
     */
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

        Mail::assertQueued(ContactForm::class, function ($mail) use ($contact) {
            return $mail->hasTo('waltprorok@gmail.com');
        });

        Mail::assertQueued(ContactForm::class, 1);
    }
}
