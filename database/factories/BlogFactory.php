<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'author_id' => 1,
            'title' => 'Test Blog',
            'slug' => 'test-blog',
            'body' => $this->faker->text(250),
            'image' => null,
            'released_on' => now()->subWeek()->format('Y-m-d H:i:s'),
        ];
    }
}

