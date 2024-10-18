<?php

use App\Models\Student;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Student::class, function (Faker $faker) {
    return [
        'student_id' => function () {
            return factory(User::class)->create()->id;
        },
        'teacher_id' => $faker->numberBetween(3, 4),
        'first_name' => function ($student) {
            return App\Models\User::find($student['student_id'])->first_name;
        },
        'last_name' => function ($student) {
            return App\Models\User::find($student['student_id'])->last_name;
        },
        'email' => function ($student) {
            return App\Models\User::find($student['student_id'])->email;
        },
        'phone' => $faker->numerify('##########'),
        'status' => $faker->numberBetween(1, 4),
    ];
});

