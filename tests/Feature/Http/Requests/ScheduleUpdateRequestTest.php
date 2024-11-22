<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\ScheduleUpdateRequest;
use App\Models\BillingRate;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ScheduleUpdateRequestTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

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
        $teacher = factory(User::class)->create(['teacher' => true, "student" => false]);
        $student = factory(User::class)->create(['student' => true]);
        $billingRate = factory(BillingRate::class)->create();
        $student = factory(Student::class)->create(['student_id' => $student->id, 'teacher_id' => $teacher->id]);

        $validator = Validator::make([
            'student_id' => $student->id,
            'billing_rate_id' => $billingRate->id,
            'title' => $this->faker->text,
            'color' => $this->faker->hexColor,
            'start_date' => $this->faker->dateTime()->format('Y-m-d'),
            'end_time' => $this->faker->dateTime()->format('H:i:s'),
            'recurrence' => 'Monthly'
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    /**
     * @return array[]
     */
    public function requestDataProvider(): array
    {
        return [
            'No Student fail' =>
                ['student_id', null],
            'No Billing Rate fail' =>
                ['billing_rate_id', null],
            'Title fail' =>
                ['title', null],
            'Color fail' =>
                ['color', null],
            'Start date fail' =>
                ['start_date', null],
            'End time fail' =>
                ['end_time', 123],
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
