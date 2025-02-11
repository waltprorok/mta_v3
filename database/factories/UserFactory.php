<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        static $password;

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password ?: $password = bcrypt('secret'),
            'admin' => false,
            'student' => false,
            'teacher' => false,
            'parent' => false,
            'terms' => true,
            'is_active' => true,
            'trial_ends_at' => now()->addMonth()->format('Y-m-d H:i:s'),
            'timezone' => 'America/New_York',
            'remember_token' => str_random(10),
        ];
    }
}
