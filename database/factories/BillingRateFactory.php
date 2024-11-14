<?php

/** @var Factory $factory */

use App\Models\BillingRate;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(BillingRate::class, function (Faker $faker) {
    return [
        'id' => 1,
        'teacher_id' => 1,
        'type' => 'monthly',
        'amount' => 100,
        'description' => 'Monthly Rate',
        'default' => false,
        'active' => true,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
