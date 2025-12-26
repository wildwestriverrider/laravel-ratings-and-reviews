# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## About

Laravel package that adds ratings and reviews functionality to any Eloquent model via polymorphic relationships. Supports Laravel 10+ and PHP 8.1+.

## Commands

```bash
# Run tests
composer test

# Run single test file
./vendor/bin/pest tests/RatingTest.php

# Run single test by name
./vendor/bin/pest --filter="test name here"

# Static analysis (PHPStan level 4)
composer analyse

# Format code (Laravel Pint)
composer format

# Test coverage
composer test-coverage
```

## Architecture

### Trait System
The package uses four traits that work in pairs:

**For models that can BE rated/reviewed (e.g., Product, Restaurant):**
- `Rateable` - adds `ratings()`, `averageRating()`, `ratingCount()`, `sumRating()`, `ratingByUser()`, `isRatedBy()`, `ratingPercentage()`
- `Reviewable` - adds `reviews()`, `approvedReviews()`, `pendingReviews()`, `reviewCount()`, `approvedReviewCount()`, `reviewByUser()`, `isReviewedBy()`

**For models that CAN give ratings/reviews (e.g., User):**
- `HasRatings` - adds `ratingsGiven()`, `rate()`, `unrate()`, `hasRated()`, `getRatingFor()`
- `HasReviews` - adds `reviewsWritten()`, `review()`, `unreview()`, `hasReviewed()`, `getReviewFor()`

### Models
- `Rating` - polymorphic model with `author` and `rateable` morph relationships, soft deletes, validation against config values
- `Review` - polymorphic model with `author` and `reviewable` morph relationships, soft deletes, `approve()`/`unapprove()` methods

### Query Scopes
- `Rating::withRating($value)` - filter by specific rating
- `Rating::between($min, $max)` - filter by rating range
- `Review::approved()` - only approved reviews
- `Review::pending()` - only unapproved reviews

### Testing
Tests use Orchestra Testbench with in-memory SQLite. The `TestCase` base class auto-registers the service provider and runs migrations from stub files in `database/migrations/`.

### Factories
Use fluent factory methods:
```php
Rating::factory()->by($user)->forRateable($product)->withRating(5)->create();
Review::factory()->by($user)->forReviewable($product)->approved()->create();
```

## Key Files

- `src/LaravelRatingsAndReviewsServiceProvider.php` - Package service provider using Spatie's laravel-package-tools
- `config/ratings-and-reviews.php` - Config with `max-rating` and `min-rating` settings (validated on save)
- `database/migrations/` - Migration stubs with indexes for performance
