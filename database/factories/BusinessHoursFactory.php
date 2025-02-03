<?php

namespace Database\Factories;

use App\Models\BusinessHours;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessHoursFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusinessHours::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'teacher_id' => 3,
            'day' => $this->faker->unique()->numberBetween(0, 6),
            'active' => $this->faker->numberBetween(0, 1),
            'open_time' => '16:00:00',
            'close_time' => '20:00:00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
