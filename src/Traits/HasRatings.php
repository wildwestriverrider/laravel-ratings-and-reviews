<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Rating;

trait HasRatings
{

    public static function bootHasRatings(): void
    {
        static::deleting(function (Model $deletingModel) {
            // check that the user can delete the rating in question
        });
    }

    public function ratingsGiven(): MorphMany
    {
        return $this->morphMany(Rating::class, 'author');
    }
}
