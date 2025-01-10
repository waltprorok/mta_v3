<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_index_page_redirect()
    {
        $response = $this->get(route('teacher.studioIndex'));

        $response->assertStatus(302);
    }

}
