<?php

/** @var Factory $factory */

use App\Models\Plan;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'name' => 'premium',
        'slug' => 'monthly',
        'stripe_plan' => $faker->text,
        'cost' => '9.95',
    ];
});
