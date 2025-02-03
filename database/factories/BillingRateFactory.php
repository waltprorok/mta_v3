<?php

namespace Database\Factories;


use App\Models\BillingRate;
use Carbon\Carbon;
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
    }
}

