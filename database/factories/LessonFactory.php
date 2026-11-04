<?php

namespace Database\Factories;

use App\Models\BillingRate;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        $startDate = Carbon::instance(
            $this->faker->dateTimeBetween('now', '+3 months')
        );

        $duration = $this->faker->randomElement([15, 30, 45, 60]);

        $endDate = $startDate->copy()->addMinutes($duration);

        return [
            'student_id' => Student::factory(),
            'teacher_id' => null,
            'billing_rate_id' => BillingRate::factory(),

            'title' => $this->faker->sentence(3),
            'color' => $this->faker->hexColor(),

            'start_date' => $startDate,
            'end_date' => $endDate,
            'interval' => $duration,

            'recurrence' => $this->faker->randomElement([
                'Once',
                'Monthly',
            ]),

            'notes' => $this->faker->optional()->sentence(),

            'complete' => false,
            'invoice_id' => null,
            'status' => Lesson::STATUS[0],
            'status_updated_at' => null,
        ];
    }
}
