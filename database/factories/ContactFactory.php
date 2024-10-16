<?php

/** @var Factory $factory */

use App\Models\Contact;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName . ' ' . $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'subject' => $faker->text(32),
        'message' => $faker->text(250),
    ];
});
