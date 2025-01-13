<?php

/** @var Factory $factory */

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Invoice::class, function () {
    return [
        'student_id' => 1,
        'teacher_id' => 3,
        'lesson_id' => '',
        'subtotal' => 0,
        'discount' => 0,
        'total' => 0,
        'balance_due' => 0,
        'payment' => 0,
        'adjustments' => 0,
        'payment_type_id' => 1,
        'check_number' => null,
        'payment_information' => null,
        'due_date' => null,
        'is_paid' => 0,
    ];
});
