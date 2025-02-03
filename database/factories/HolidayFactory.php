<?php

namespace Database\Factories;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Factories\Factory;

class HolidayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Holiday::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'teacher_id' => '1',
            'title' => 'Christmas',
            'color' => '#52BE80',
            'start_date' => '2025-12-25 00:00:01',
            'end_date' => '2025-12-25 11:59:59',
            'all_day' => true,
        ];
    }
}
