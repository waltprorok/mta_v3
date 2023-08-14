<?php

/** @var Factory $factory */

use App\Models\BusinessHours;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(BusinessHours::class, function (Faker $faker) {
    return [
        'teacher_id' => 3,
        'day' => $faker->unique()->numberBetween(0, 6),
        'active' => $faker->numberBetween(0, 1),
        'open_time' => '16:00',
        'close_time' => '20:00',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
