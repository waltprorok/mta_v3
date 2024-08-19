<?php

/** @var Factory $factory */

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factory;

$factory->define(PaymentType::class, function () {
    return [
        'id' => 1,
        'name' => 'Cash'
    ];
});
