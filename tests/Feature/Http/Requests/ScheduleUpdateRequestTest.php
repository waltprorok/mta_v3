<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\ScheduleUpdateRequest;
use App\Models\BillingRate;
use App\Models\Lesson;
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

    public ScheduleUpdateRequest $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new ScheduleUpdateRequest();
    }

    public function test_verifies_authorized()
    {
        $user = User::factory()->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $teacher = User::factory()->create(['teacher' => true]);
        $studentUser = User::factory()->create(['student' => true]);
        $billingRate = BillingRate::factory()->create();

        $student = Student::factory()->create([
            'student_id' => $studentUser->id,
            'teacher_id' => $teacher->id,
        ]);

        $lesson = Lesson::factory()->create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'billing_rate_id' => $billingRate->id,
        ]);

        $validator = Validator::make([
            'id' => $lesson->id,
            'student_id' => $student->id,
            'billing_rate_id' => $billingRate->id,
            'title' => $this->faker->text,
            'color' => $this->faker->hexColor,
            'start_date' => $lesson->start_date->format('Y-m-d'),
            'start_time' => '16:00:00',
            'end_time' => '30',
            'recurrence' => 'Monthly',
        ], $this->request->rules());

        $this->assertTrue(
            $validator->passes(),
            json_encode($validator->errors()->toArray(), JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array[]
     */
    public static function requestDataProvider(): array
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
