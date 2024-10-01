<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UpdateStudentRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateStudentRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new UpdateStudentRequest();
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
            'phone' => $this->faker->phoneNumber,
            'parent_first_name' => $this->faker->firstName,
            'parent_last_name' => $this->faker->lastName,
            'parent_email' => $this->faker->unique()->safeEmail,
            'zip' => 15116,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    /**
     * @return array[]
     */
    public function requestDataProvider(): array
    {
        return [
            'First name fail' =>
                ['first_name', null],
            'Last name fail' =>
                ['last_name', null],
            'Email fail' =>
                ['email', 'john.snow'],
            'Email integer fail' =>
                ['email', 1234],
            'Phone not string fail' =>
                ['phone', 5551231234],
            'Zip min fail' =>
                ['zip', 1234],
        ];
    }

    /**
     * @dataProvider requestDataProvider
     */
    public function test_request_fail($key, $value)
    {
        $validator = Validator::make([
            $key => $value,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains($key, $validator->errors()->keys());
    }
}
