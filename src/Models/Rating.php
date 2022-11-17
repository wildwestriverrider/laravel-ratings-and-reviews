<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Rating extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }
}
