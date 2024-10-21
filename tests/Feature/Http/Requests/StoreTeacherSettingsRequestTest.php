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
            'zip' => $this->faker->postcode,
            'email' => $this->faker->unique()->email,
            'phone' => $this->faker->numerify('###-###-####'),
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

    /**
     * @return array[]
     */
    public function requestDataProvider(): array
    {
        return [
            'Studio name required fail' =>
                ['studio_name', null],
            'Studio name string fail' =>
                ['studio_name', 1],
            'First name fail' =>
                ['first_name', null],
            'First name string  fail' =>
                ['first_name', 1],
            'Last name fail' =>
                ['last_name', null],
            'Last name string fail' =>
                ['last_name', 1],
            'Address fail' =>
                ['address', null],
            'Address string fail' =>
                ['address', 1],
            'City fail' =>
                ['city', null],
            'City string fail' =>
                ['city', 1],
            'State fail' =>
                ['state', null],
            'State string fail' =>
                ['state', 1],
            'Zip fail' =>
                ['zip', null],
            'Zip min fail' =>
                ['zip', 1234],
            'Email fail' =>
                ['email', 'john.snow'],
            'Email null fail' =>
                ['email', null],
            'Phone not string fail' =>
                ['phone', 5551231234],
            'Logo fail' =>
                ['logo', 'test.docx'],
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
