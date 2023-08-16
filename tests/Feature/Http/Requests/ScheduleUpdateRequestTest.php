<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\ScheduleUpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ScheduleUpdateRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new ScheduleUpdateRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'title' => $this->faker->text,
            'start_date' => $this->faker->dateTime()->format('Y-m-d'),
            'end_time' => $this->faker->dateTime()->format('H:i:s'),
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

    public function test_start_date_fail()
    {
        $validator = Validator::make([
            'start_date' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('start_date', $validator->errors()->keys());
    }

    public function test_end_time_fail()
    {
        $validator = Validator::make([
            'end_time' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('end_time', $validator->errors()->keys());
    }
}
