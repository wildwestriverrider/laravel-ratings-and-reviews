<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Review;

trait HasReviews
{
    public function reviewsWritten(): MorphMany
    {
        return $this->morphMany(Review::class, 'author');
    }
}
