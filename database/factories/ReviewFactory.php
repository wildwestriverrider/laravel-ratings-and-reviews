<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Review;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(),
            'author' => User::factory(),
            'approved' => $this->faker->boolean(),
        ];
    }
}
