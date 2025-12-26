<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Rating;

trait Rateable
{
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    /**
     * Get the average rating for this model.
     */
    public function averageRating(): ?float
    {
        return $this->ratings()->average('rating');
    }

    /**
     * Get the total number of ratings for this model.
     */
    public function ratingCount(): int
    {
        return $this->ratings()->count();
    }

    /**
     * Get the sum of all ratings for this model.
     */
    public function sumRating(): int
    {
        return (int) $this->ratings()->sum('rating');
    }

    /**
     * Get the rating given by a specific user/author.
     */
    public function ratingByUser(Model $author): ?Rating
    {
        return $this->ratings()
            ->where('author_id', $author->getKey())
            ->where('author_type', $author->getMorphClass())
            ->first();
    }

    /**
     * Check if a specific user/author has rated this model.
     */
    public function isRatedBy(Model $author): bool
    {
        return $this->ratings()
            ->where('author_id', $author->getKey())
            ->where('author_type', $author->getMorphClass())
            ->exists();
    }

    /**
     * Get the percentage of ratings for a specific rating value.
     */
    public function ratingPercentage(int $ratingValue): float
    {
        $total = $this->ratingCount();

        if ($total === 0) {
            return 0.0;
        }

        $count = $this->ratings()->where('rating', $ratingValue)->count();

        return round(($count / $total) * 100, 2);
    }
}
