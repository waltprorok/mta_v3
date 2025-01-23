<?php

/** @var Factory $factory */

use App\Models\Blog;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Blog::class, function (Faker $faker) {
    return [
        'author_id' => 1,
        'title' => 'Test Blog',
        'slug' => 'test-blog',
        'body' => $faker->text(250),
        'image' => null,
        'released_on' => now()->subWeek()->format('Y-m-d H:i:s'),
    ];
});
