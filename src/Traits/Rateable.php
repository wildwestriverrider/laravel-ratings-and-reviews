<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Rating;

trait Rateable
{
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function averageRating()
    {
        return $this->ratings()->average('rating');
    }
}
