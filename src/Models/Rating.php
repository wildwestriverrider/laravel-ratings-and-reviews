<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'rateable_id',
        'rateable_type',
        'author_id',
        'author_type',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Rating $rating) {
            $min = config('ratings-and-reviews.min-rating', 1);
            $max = config('ratings-and-reviews.max-rating', 5);

            if ($rating->rating < $min || $rating->rating > $max) {
                throw new InvalidArgumentException(
                    "Rating must be between {$min} and {$max}."
                );
            }
        });
    }

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by specific rating value.
     */
    public function scopeWithRating(Builder $query, int $value): Builder
    {
        return $query->where('rating', $value);
    }

    /**
     * Scope to filter ratings within a range.
     */
    public function scopeBetween(Builder $query, int $min, int $max): Builder
    {
        return $query->whereBetween('rating', [$min, $max]);
    }
}
