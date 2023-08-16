<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreScheduleApptRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreScheduleApptRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new StoreScheduleApptRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'student_id' => $this->faker->text,
            'billing_rate_id' => $this->faker->text,
            'title' => $this->faker->text,
            'start_date' => $this->faker->dateTime->format('Y-m-d'),
            'start_time' => $this->faker->dateTime->format('h:i:s'),
            'end_time' => $this->faker->dateTime->format('h:i:s'),
            'recurrence' => $this->faker->text,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_student_id_fail()
    {
        $validator = Validator::make([
            'student_id' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('student_id', $validator->errors()->keys());
    }

    public function test_billing_rate_id_fail()
    {
        $validator = Validator::make([
            'billing_rate_id' => 1,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('billing_rate_id', $validator->errors()->keys());
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

    public function test_start_time_fail()
    {
        $validator = Validator::make([
            'start_time' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('start_time', $validator->errors()->keys());
    }

    public function test_end_time_fail()
    {
        $validator = Validator::make([
            'end_time' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('end_time', $validator->errors()->keys());
    }

    public function test_recurrence_fail()
    {
        $validator = Validator::make([
            'recurrence' => null,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('recurrence', $validator->errors()->keys());
    }
}
