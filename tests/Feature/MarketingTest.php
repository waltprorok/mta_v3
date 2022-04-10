<?php

namespace Tests\Feature;

use Tests\TestCase;

class MarketingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_home_page_200()
    {
        $response = $this->call('GET', '/');

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
}
