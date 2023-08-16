<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateUserProfileRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new UpdateUserProfileRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_first_name_fail()
    {
        $validator = Validator::make([
            'first_name' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('first_name', $validator->errors()->keys());
    }

    public function test_last_name_fail()
    {
        $validator = Validator::make([
            'last_name' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('last_name', $validator->errors()->keys());
    }

    public function test_email_fail()
    {
        $validator = Validator::make([
            'email' => 'john.snow',
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('email', $validator->errors()->keys());
    }

    public function test_email_null_fail()
    {
        $validator = Validator::make([
            'email' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('email', $validator->errors()->keys());
    }
}
