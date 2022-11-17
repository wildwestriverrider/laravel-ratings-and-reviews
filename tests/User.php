<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Tests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Wildwestriverrider\LaravelRatingsAndReviews\Traits\HasRatings;
use Wildwestriverrider\LaravelRatingsAndReviews\Traits\HasReviews;
use Wildwestriverrider\LaravelRatingsAndReviews\Traits\Rateable;
use Wildwestriverrider\LaravelRatingsAndReviews\Traits\Reviewable;

class User extends Model implements AuthorizableContract, AuthenticatableContract {
    use HasFactory, HasReviews, HasRatings, Reviewable, Rateable, Authorizable, Authenticatable;

    protected $guarded = [];

    protected $table = 'users';
}
