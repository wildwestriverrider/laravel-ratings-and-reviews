<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Rating;

trait HasRatings
{
    public function ratingsGiven(): MorphMany
    {
        return $this->morphMany(Rating::class, 'author');
    }

    /**
     * Rate a model. Creates a new rating or updates an existing one.
     */
    public function rate(Model $rateable, int $ratingValue): Rating
    {
        $rating = $this->getRatingFor($rateable);

        if ($rating) {
            $rating->update(['rating' => $ratingValue]);

            return $rating->fresh();
        }

        return $this->ratingsGiven()->create([
            'rating' => $ratingValue,
            'rateable_id' => $rateable->getKey(),
            'rateable_type' => $rateable->getMorphClass(),
        ]);
    }

    /**
     * Remove a rating from a model.
     */
    public function unrate(Model $rateable): bool
    {
        return (bool) $this->ratingsGiven()
            ->where('rateable_id', $rateable->getKey())
            ->where('rateable_type', $rateable->getMorphClass())
            ->delete();
    }

    /**
     * Check if this model has rated the given rateable.
     */
    public function hasRated(Model $rateable): bool
    {
        return $this->ratingsGiven()
            ->where('rateable_id', $rateable->getKey())
            ->where('rateable_type', $rateable->getMorphClass())
            ->exists();
    }

    /**
     * Get the rating this model gave to the specified rateable.
     */
    public function getRatingFor(Model $rateable): ?Rating
    {
        return $this->ratingsGiven()
            ->where('rateable_id', $rateable->getKey())
            ->where('rateable_type', $rateable->getMorphClass())
            ->first();
    }
}
