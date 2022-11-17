<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Rating;

trait HasRatings
{
    public function ratingsGiven(): MorphMany
    {
        return $this->morphMany(Rating::class, 'author');
    }
}
