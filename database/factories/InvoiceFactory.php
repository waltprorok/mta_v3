<?php

/** @var Factory $factory */

use App\Models\Invoice;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'id' => 1,
        'student_id' => 1,
        'teacher_id' => 3,
        'lesson_id' => '',
        'subtotal' => 0,
        'discount' => 0,
        'total' => 0,
        'balance_due' => 0,
        'payment' => 0,
        'adjustments' => 0,
        'is_paid' => 0,
    ];
});
