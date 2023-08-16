<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreContactRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreContactRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new StoreContactRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'subject' => $this->faker->sentence(),
            'message' => $this->faker->sentences,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_name_fail()
    {
        $validator = Validator::make([
            'name' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('name', $validator->errors()->keys());
    }

    public function test_not_email_fail()
    {
        $validator = Validator::make([
            'email' => 'john.snow',
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('email', $validator->errors()->keys());
    }

    public function test_email_fail()
    {
        $validator = Validator::make([
            'email' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('email', $validator->errors()->keys());
    }

    public function test_subject_fail()
    {
        $validator = Validator::make([
            'subject' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('subject', $validator->errors()->keys());
    }

    public function test_subject_min_fail()
    {
        $validator = Validator::make([
            'email' => 'ab',
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('email', $validator->errors()->keys());
    }

    public function test_message_fail()
    {
        $validator = Validator::make([
            'message' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('message', $validator->errors()->keys());
    }

    public function test_message_min_fail()
    {
        $validator = Validator::make([
            'message' => 'ab',
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('message', $validator->errors()->keys());
    }
}
