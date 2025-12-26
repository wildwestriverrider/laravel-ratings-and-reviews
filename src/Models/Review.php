<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'content',
        'approved',
        'reviewable_id',
        'reviewable_type',
        'author_id',
        'author_type',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter only approved reviews.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approved', true);
    }

    /**
     * Scope to filter only pending (unapproved) reviews.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('approved', false);
    }

    /**
     * Mark the review as approved.
     */
    public function approve(): bool
    {
        return $this->update(['approved' => true]);
    }

    /**
     * Mark the review as unapproved/pending.
     */
    public function unapprove(): bool
    {
        return $this->update(['approved' => false]);
    }
}
