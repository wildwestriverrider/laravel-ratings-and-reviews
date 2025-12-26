<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Review;

trait HasReviews
{
    public function reviewsWritten(): MorphMany
    {
        return $this->morphMany(Review::class, 'author');
    }

    /**
     * Review a model. Creates a new review or updates an existing one.
     */
    public function review(Model $reviewable, string $content): Review
    {
        $review = $this->getReviewFor($reviewable);

        if ($review) {
            $review->update(['content' => $content]);

            return $review->fresh();
        }

        return $this->reviewsWritten()->create([
            'content' => $content,
            'approved' => false,
            'reviewable_id' => $reviewable->getKey(),
            'reviewable_type' => $reviewable->getMorphClass(),
        ]);
    }

    /**
     * Remove a review from a model.
     */
    public function unreview(Model $reviewable): bool
    {
        return (bool) $this->reviewsWritten()
            ->where('reviewable_id', $reviewable->getKey())
            ->where('reviewable_type', $reviewable->getMorphClass())
            ->delete();
    }

    /**
     * Check if this model has reviewed the given reviewable.
     */
    public function hasReviewed(Model $reviewable): bool
    {
        return $this->reviewsWritten()
            ->where('reviewable_id', $reviewable->getKey())
            ->where('reviewable_type', $reviewable->getMorphClass())
            ->exists();
    }

    /**
     * Get the review this model wrote for the specified reviewable.
     */
    public function getReviewFor(Model $reviewable): ?Review
    {
        return $this->reviewsWritten()
            ->where('reviewable_id', $reviewable->getKey())
            ->where('reviewable_type', $reviewable->getMorphClass())
            ->first();
    }
}
