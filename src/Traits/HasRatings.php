<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Rating;

trait HasRatings
{

    public static function bootHasRatings(): void
    {
        static::deleting(function (Model $deletingModel) {
            // check that the user can delete the rating in question
            if (Gate::forUser(Auth::user())->denies('delete-rating', $deletingModel)) {
                // The user can't update the post...
                throw new AuthorizationException('User not authorized to delete this rating');
            }
        });
    }

    public function ratingsGiven(): MorphMany
    {
        return $this->morphMany(Rating::class, 'author');
    }
}
