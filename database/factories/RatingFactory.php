<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Rating;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(
                config('ratings-and-reviews.min-rating', 1),
                config('ratings-and-reviews.max-rating', 5)
            ),
        ];
    }

    /**
     * Set the author of the rating.
     */
    public function by(Model $author): static
    {
        return $this->state(fn () => [
            'author_id' => $author->getKey(),
            'author_type' => $author->getMorphClass(),
        ]);
    }

    /**
     * Set the rateable model.
     */
    public function forRateable(Model $rateable): static
    {
        return $this->state(fn () => [
            'rateable_id' => $rateable->getKey(),
            'rateable_type' => $rateable->getMorphClass(),
        ]);
    }

    /**
     * Set a specific rating value.
     */
    public function withRating(int $rating): static
    {
        return $this->state(fn () => [
            'rating' => $rating,
        ]);
    }
}
