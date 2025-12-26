<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Review;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(),
            'approved' => false,
        ];
    }

    /**
     * Set the author of the review.
     */
    public function by(Model $author): static
    {
        return $this->state(fn () => [
            'author_id' => $author->getKey(),
            'author_type' => $author->getMorphClass(),
        ]);
    }

    /**
     * Set the reviewable model.
     */
    public function forReviewable(Model $reviewable): static
    {
        return $this->state(fn () => [
            'reviewable_id' => $reviewable->getKey(),
            'reviewable_type' => $reviewable->getMorphClass(),
        ]);
    }

    /**
     * Mark the review as approved.
     */
    public function approved(): static
    {
        return $this->state(fn () => [
            'approved' => true,
        ]);
    }

    /**
     * Mark the review as pending (unapproved).
     */
    public function pending(): static
    {
        return $this->state(fn () => [
            'approved' => false,
        ]);
    }

    /**
     * Set specific content for the review.
     */
    public function withContent(string $content): static
    {
        return $this->state(fn () => [
            'content' => $content,
        ]);
    }
}
