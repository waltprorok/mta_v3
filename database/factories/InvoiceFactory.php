<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
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
    }
}
