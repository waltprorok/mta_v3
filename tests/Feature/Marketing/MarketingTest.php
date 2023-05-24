<?php

namespace Tests\Feature\Marketing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_200()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_faq_page_200()
    {
        $response = $this->get('/faq');

        $response->assertStatus(200);
    }

    public function test_contact_page_200()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    public function test_blog_page_200()
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
    }

    public function test_blog_scheduling_students_page_200()
    {
        $this->seed();

        $response = $this->get('/blog/scheduling-students');

        $response->assertStatus(200);
    }

    public function test_pricing_page_200()
    {
        $response = $this->get('/pricing');

        $response->assertStatus(200);
    }

    public function test_terms_page_200()
    {
        $response = $this->get('/terms');

        $response->assertStatus(200);
    }

    public function test_privacy_page_200()
    {
        $response = $this->get('/privacy');

        $response->assertStatus(200);
    }
}
