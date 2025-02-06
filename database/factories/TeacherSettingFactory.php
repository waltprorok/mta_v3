<?php

namespace Database\Factories;

use App\Models\TeacherSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeacherSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'teacher_id' => 1,
            'calendar' => 'dayGridMonth',
            'calendar_min_time' => '08:00:00',
            'calendar_max_time' => '22:00:00',
            'auto_schedule_new_active_students' => false,
        ];
    }
}
