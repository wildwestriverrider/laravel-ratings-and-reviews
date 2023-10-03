<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Tests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Wildwestriverrider\LaravelRatingsAndReviews\Traits\HasRatings;
use Wildwestriverrider\LaravelRatingsAndReviews\Traits\HasReviews;
use Wildwestriverrider\LaravelRatingsAndReviews\Traits\Rateable;
use Wildwestriverrider\LaravelRatingsAndReviews\Traits\Reviewable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, HasRatings, HasReviews, Rateable, Reviewable;

    protected $guarded = [];

    protected $table = 'users';
}
