<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Rating;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(1, 5),
            'user' => User::factory(),
        ];
    }
}
