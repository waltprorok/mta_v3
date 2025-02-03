<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'student_id' => function () {
                return User::factory()->create()->id;
            },
            'teacher_id' => $this->faker->numberBetween(3, 4),
            'first_name' => function ($student) {
                return User::find($student['student_id'])->first_name;
            },
            'last_name' => function ($student) {
                return User::find($student['student_id'])->last_name;
            },
            'email' => function ($student) {
                return User::find($student['student_id'])->email;
            },
            'phone' => $this->faker->numerify('##########'),
            'status' => $this->faker->numberBetween(1, 4),
        ];
    }
}

