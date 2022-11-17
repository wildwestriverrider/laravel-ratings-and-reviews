<?php

namespace Wildwestriverrider\LaravelRatingsAndReviews\Commands;

use Illuminate\Console\Command;

class LaravelRatingsAndReviewsCommand extends Command
{
    public $signature = 'laravel-ratings-and-reviews';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
