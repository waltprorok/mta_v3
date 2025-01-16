<?php

/** @var Factory $factory */

use App\Models\Instrument;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Instrument::class, function (Faker $faker) {
    return [
        'teacher_id' => '1',
        'name' => 'Guitar'
    ];
});
