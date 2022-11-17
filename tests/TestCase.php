<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Wildwestriverrider\LaravelRatingsAndReviews\LaravelRatingsAndReviewsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Wildwestriverrider\\LaravelRatingsAndReviews\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelRatingsAndReviewsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Schema::dropAllTables();
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../database/migrations/create_users_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_ratings_and_reviews_table.php.stub';
        $migration->up();
    }
}
