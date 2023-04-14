<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Purpose to call
 */
class MarketingTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_200()
    {
        $response = $this->call('get', '/');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_faq_page_200()
    {
        $response = $this->call('get', '/faq');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_contact_page_200()
    {
        $response = $this->call('get', '/contact');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_blog_page_200()
    {
        $response = $this->call('get', '/blog');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_blog_scheduling_students_page_200()
    {
        $this->seed();

        $response = $this->call('get', '/blog/scheduling-students');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_pricing_page_200()
    {
        $response = $this->call('get', '/pricing');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_register_page_200()
    {
        $response = $this->call('get', '/register');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_terms_page_200()
    {
        $response = $this->call('get', '/terms');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_privacy_page_200()
    {
        $response = $this->call('get', '/privacy');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_password_reset_page_200()
    {
        $response = $this->call('get', '/password/reset');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_dashboard_page_200()
    {
        $response = $this->call('get', '/dashboard');

        $this->assertEquals(302, $response->getStatusCode());
    }
}
