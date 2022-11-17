<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wildwestriverrider\LaravelRatingsAndReviews\LaravelRatingsAndReviews
 */
class LaravelRatingsAndReviews extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Wildwestriverrider\LaravelRatingsAndReviews\LaravelRatingsAndReviews::class;
    }
}
