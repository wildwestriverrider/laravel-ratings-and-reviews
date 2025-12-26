<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Review;

trait Reviewable
{
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get only approved reviews.
     */
    public function approvedReviews(): MorphMany
    {
        return $this->reviews()->approved();
    }

    /**
     * Get only pending (unapproved) reviews.
     */
    public function pendingReviews(): MorphMany
    {
        return $this->reviews()->pending();
    }

    /**
     * Get the total number of reviews for this model.
     */
    public function reviewCount(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Get the number of approved reviews.
     */
    public function approvedReviewCount(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get the review written by a specific user/author.
     */
    public function reviewByUser(Model $author): ?Review
    {
        return $this->reviews()
            ->where('author_id', $author->getKey())
            ->where('author_type', $author->getMorphClass())
            ->first();
    }

    /**
     * Check if a specific user/author has reviewed this model.
     */
    public function isReviewedBy(Model $author): bool
    {
        return $this->reviews()
            ->where('author_id', $author->getKey())
            ->where('author_type', $author->getMorphClass())
            ->exists();
    }
}
