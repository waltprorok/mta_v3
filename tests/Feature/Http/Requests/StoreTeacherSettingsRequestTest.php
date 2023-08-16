<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreTeacherSettingsRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreTeacherSettingsRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new StoreTeacherSettingsRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'studio_name' => 'Studio Name',
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'zip' => 15116,
            'email' => $this->faker->unique()->email,
            'phone' => $this->faker->phoneNumber,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_studio_name_fail()
    {
        $validator = Validator::make([
            'studio_name' => 1,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('studio_name', $validator->errors()->keys());
    }

    public function test_first_name_fail()
    {
        $validator = Validator::make([
            'first_name' => 1,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('first_name', $validator->errors()->keys());
    }

    public function test_last_name_fail()
    {
        $validator = Validator::make([
            'last_name' => 1,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('last_name', $validator->errors()->keys());
    }

    public function test_address_fail()
    {
        $validator = Validator::make([
            'address' => 1,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('address', $validator->errors()->keys());
    }

    public function test_city_fail()
    {
        $validator = Validator::make([
            'city' => 1,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('city', $validator->errors()->keys());
    }

    public function test_state_fail()
    {
        $validator = Validator::make([
            'state' => 1,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('state', $validator->errors()->keys());
    }

    public function test_zip_fail()
    {
        $validator = Validator::make([
            'zip' => 123456,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('zip', $validator->errors()->keys());
    }

    public function test_email_fail()
    {
        $validator = Validator::make([
            'email' => 'john.snow',
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('email', $validator->errors()->keys());
    }

    public function test_phone_fail()
    {
        $validator = Validator::make([
            'phone' => 5551231234,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('phone', $validator->errors()->keys());
    }
}
