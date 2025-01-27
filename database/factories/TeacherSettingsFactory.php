<?php

/** @var Factory $factory */

use App\Models\TeacherSetting;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(TeacherSetting::class, function (Faker $faker) {
    return [
        'teacher_id' => 1,
        'calendar' => 'month',
        'calendar_min_time' => '08:00:00',
        'calendar_max_time' => '22:00:00',
        'auto_schedule_new_active_students' => false,
    ];
});
