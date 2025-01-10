<?php

/** @var Factory $factory */

use App\Models\Teacher;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Teacher::class, function (Faker $faker) {
    return [
        'teacher_id' => $faker->numerify(),
        'studio_name' =>$faker->name,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
    ];
});
