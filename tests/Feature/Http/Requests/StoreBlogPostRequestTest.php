<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreBlogPostRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreBlogPostRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new StoreBlogPostRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);
        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'title' => $this->faker->word,
            'slug' => $this->faker->word,
            'body' => $this->faker->paragraphs,
            'released_on' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_title_fail()
    {
        $validator = Validator::make([
            'title' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('title', $validator->errors()->keys());
    }

    public function test_slug_fail()
    {
        $validator = Validator::make([
            'slug' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('slug', $validator->errors()->keys());
    }

    public function test_body_fail()
    {
        $validator = Validator::make([
            'body' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('body', $validator->errors()->keys());
    }

    public function test_released_on_fail()
    {
        $validator = Validator::make([
            'released_on' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('released_on', $validator->errors()->keys());
    }
}
