<?php

namespace Database\Factories;


use App\Models\BillingRate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillingRate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'teacher_id' => User::factory()->state([
                'teacher' => true,
            ]),
            'type' => 'monthly',
            'amount' => 100,
            'description' => 'Monthly Rate',
            'default' => false,
            'active' => true,
        ];
    }
}

