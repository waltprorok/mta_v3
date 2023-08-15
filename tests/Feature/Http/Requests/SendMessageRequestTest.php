<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\SendMessageRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SendMessageRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new SendMessageRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'to' => 1,
            'subject' => $this->faker->text,
            'message' => $this->faker->sentences,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_to_fail()
    {
        $validator = Validator::make([
            'to' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('to', $validator->errors()->keys());
    }

    public function test_subject_fail()
    {
        $validator = Validator::make([
            'subject' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('subject', $validator->errors()->keys());
    }

    public function test_message_fail()
    {
        $validator = Validator::make([
            'message' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('message', $validator->errors()->keys());
    }
}
