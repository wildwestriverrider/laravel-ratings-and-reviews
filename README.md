# Ratings and reviews in Laravel 10+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wildwestriverrider/laravel-ratings-and-reviews.svg?style=flat-square)](https://packagist.org/packages/wildwestriverrider/laravel-ratings-and-reviews)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/wildwestriverrider/laravel-ratings-and-reviews/run-tests?label=tests)](https://github.com/wildwestriverrider/laravel-ratings-and-reviews/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/wildwestriverrider/laravel-ratings-and-reviews/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/wildwestriverrider/laravel-ratings-and-reviews/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wildwestriverrider/laravel-ratings-and-reviews.svg?style=flat-square)](https://packagist.org/packages/wildwestriverrider/laravel-ratings-and-reviews)

This package allows you to add ratings and reviews to any model in your Laravel application.

## Installation

You can install the package via composer:

```bash
composer require wildwestriverrider/laravel-ratings-and-reviews
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="ratings-and-reviews-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="ratings-and-reviews-config"
```

This is the contents of the published config file:

```php
return [
    'max-rating' => 5,
    'min-rating' => 1
];
```

## Usage
### Traits

HasRatings - add this to the user model that will be giving ratings

HasReviews - add this to the user model that will be giving reviews

Rateable - add this to any model that should be rateable

Reviewable - add this to any model that should be reviewable

```php
$laravelRatingsAndReviews = new Wildwestriverrider\LaravelRatingsAndReviews();
```

## Testing

```bash
composer test
```

[//]: # (## Changelog)

[//]: # ()
[//]: # (Please see [CHANGELOG]&#40;CHANGELOG.md&#41; for more information on what has changed recently.)

[//]: # ()
[//]: # (## Contributing)

[//]: # ()
[//]: # (Please see [CONTRIBUTING]&#40;CONTRIBUTING.md&#41; for details.)

[//]: # ()
[//]: # (## Security Vulnerabilities)

[//]: # ()
[//]: # (Please review [our security policy]&#40;../../security/policy&#41; on how to report security vulnerabilities.)

[//]: # ()
[//]: # (## Credits)

[//]: # ()
[//]: # (- [James Sweeney]&#40;https://github.com/wildwestriverrider&#41;)

[//]: # (- [All Contributors]&#40;../../contributors&#41;)

[//]: # ()
[//]: # (## License)

[//]: # ()
[//]: # (The MIT License &#40;MIT&#41;. Please see [License File]&#40;LICENSE.md&#41; for more information.)
