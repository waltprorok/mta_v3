<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Purpose to call
 */

class MarketingTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function home_page_200()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function faq_page_200()
    {
        $response = $this->call('GET', '/faq');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function contact_page_200()
    {
        $response = $this->call('GET', '/contact');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function blog_page_200()
    {
        $response = $this->call('GET', '/blog');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function blog_scheduling_students_page_200()
    {
        $response = $this->call('GET', '/blog/scheduling-students');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function pricing_page_200()
    {
        $response = $this->call('GET', '/pricing');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function login_page_200()
    {
        $response = $this->call('GET', '/login');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function register_page_200()
    {
        $response = $this->call('GET', '/register');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function terms_page_200()
    {
        $response = $this->call('GET', '/terms');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function privacy_page_200()
    {
        $response = $this->call('GET', '/privacy');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function password_reset_page_200()
    {
        $response = $this->call('GET', '/password/reset');

        $this->assertEquals(200, $response->getStatusCode());
    }
}