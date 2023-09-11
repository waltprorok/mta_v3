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

    /**
     * @return array[]
     */
    public function requestDataProvider(): array
    {
        return [
            'Billing rate id fail' =>
                ['billing_rate_id', 1],
            'Title null fail' =>
                ['title', null],
            'Start date fail' =>
                ['start_date', null],
            'Start time fail' =>
                ['start_time', null],
            'End time fail' =>
                ['end_time', null],
            'Recurrence fail' =>
                ['recurrence', null],
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
