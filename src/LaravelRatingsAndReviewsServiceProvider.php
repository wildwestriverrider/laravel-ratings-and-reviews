<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wildwestriverrider\LaravelRatingsAndReviews\Commands\LaravelRatingsAndReviewsCommand;

class LaravelRatingsAndReviewsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-ratings-and-reviews')
            ->hasConfigFile()
            ->hasMigration('create_ratings_and_reviews_table');
        //->hasCommand(LaravelRatingsAndReviewsCommand::class);
    }
}
