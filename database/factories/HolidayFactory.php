<?php

/** @var Factory $factory */

use App\Models\Holiday;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Holiday::class, function (Faker $faker) {
    return [
        'teacher_id' => '1',
        'title' => 'Christmas',
        'color' => '#52BE80',
        'start_date' => '2025-12-25 00:00:01',
        'end_date' => '2025-12-25 11:59:59',
        'all_day' => true,
    ];
});
