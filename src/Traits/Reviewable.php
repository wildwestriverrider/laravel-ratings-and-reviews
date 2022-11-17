<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Review;

trait Reviewable
{
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
