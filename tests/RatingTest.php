<?php

use Illuminate\Database\QueryException;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Rating;
use Wildwestriverrider\LaravelRatingsAndReviews\Tests\User;

// ==========================================
// Basic Rating Creation Tests
// ==========================================

test('users can create ratings', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $rating = $user->ratingsGiven()->create([
        'rateable_id' => $otherUser->id,
        'rateable_type' => get_class($otherUser),
        'rating' => 3,
    ]);

    expect($user->ratingsGiven()->first()->rateable->id)->toBe($otherUser->id);
    expect($rating->author->id)->toBe($user->id);
});

test('users can create one rating per rateable', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->ratingsGiven()->create([
        'rateable_id' => $otherUser->id,
        'rateable_type' => get_class($otherUser),
        'rating' => 3,
    ]);

    expect(fn () => $user->ratingsGiven()->create([
        'rateable_id' => $otherUser->id,
        'rateable_type' => get_class($otherUser),
        'rating' => 3,
    ]))->toThrow(QueryException::class);
});

// ==========================================
// Rating Validation Tests
// ==========================================

test('rating must be within configured range', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect(fn () => $user->rate($otherUser, 0))->toThrow(\InvalidArgumentException::class);
    expect(fn () => $user->rate($otherUser, 6))->toThrow(\InvalidArgumentException::class);
    expect(fn () => $user->rate($otherUser, -1))->toThrow(\InvalidArgumentException::class);
});

test('rating within range is accepted', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $rating = $user->rate($otherUser, 1);
    expect($rating->rating)->toBe(1);

    $user2 = User::factory()->create();
    $rating2 = $user2->rate($otherUser, 5);
    expect($rating2->rating)->toBe(5);
});

// ==========================================
// HasRatings Trait Helper Methods
// ==========================================

test('user can rate a model using rate() method', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $rating = $user->rate($otherUser, 4);

    expect($rating)->toBeInstanceOf(Rating::class);
    expect($rating->rating)->toBe(4);
    expect($rating->author->id)->toBe($user->id);
    expect($rating->rateable->id)->toBe($otherUser->id);
});

test('rate() updates existing rating instead of creating duplicate', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->rate($otherUser, 3);
    $updatedRating = $user->rate($otherUser, 5);

    expect($user->ratingsGiven()->count())->toBe(1);
    expect($updatedRating->rating)->toBe(5);
});

test('user can unrate a model', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->rate($otherUser, 4);
    expect($user->ratingsGiven()->count())->toBe(1);

    $result = $user->unrate($otherUser);

    expect($result)->toBeTrue();
    expect($user->ratingsGiven()->count())->toBe(0);
});

test('hasRated returns true when user has rated', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->hasRated($otherUser))->toBeFalse();

    $user->rate($otherUser, 4);

    expect($user->hasRated($otherUser))->toBeTrue();
});

test('getRatingFor returns the rating for specific rateable', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->getRatingFor($otherUser))->toBeNull();

    $user->rate($otherUser, 4);

    $rating = $user->getRatingFor($otherUser);
    expect($rating)->toBeInstanceOf(Rating::class);
    expect($rating->rating)->toBe(4);
});

// ==========================================
// Rateable Trait Tests
// ==========================================

test('rateables have an average rating', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $anotherUser = User::factory()->create();

    $user->rate($otherUser, 3);
    $anotherUser->rate($otherUser, 1);

    expect($otherUser->averageRating())->toEqual(2.0);
});

test('averageRating returns null when no ratings', function () {
    $user = User::factory()->create();

    expect($user->averageRating())->toBeNull();
});

test('ratingCount returns total number of ratings', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $anotherUser = User::factory()->create();

    expect($otherUser->ratingCount())->toBe(0);

    $user->rate($otherUser, 3);
    expect($otherUser->ratingCount())->toBe(1);

    $anotherUser->rate($otherUser, 5);
    expect($otherUser->ratingCount())->toBe(2);
});

test('sumRating returns sum of all ratings', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $anotherUser = User::factory()->create();

    $user->rate($otherUser, 3);
    $anotherUser->rate($otherUser, 4);

    expect($otherUser->sumRating())->toBe(7);
});

test('ratingByUser returns rating from specific user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($otherUser->ratingByUser($user))->toBeNull();

    $user->rate($otherUser, 4);

    $rating = $otherUser->ratingByUser($user);
    expect($rating)->toBeInstanceOf(Rating::class);
    expect($rating->rating)->toBe(4);
});

test('isRatedBy checks if specific user has rated', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($otherUser->isRatedBy($user))->toBeFalse();

    $user->rate($otherUser, 4);

    expect($otherUser->isRatedBy($user))->toBeTrue();
});

test('ratingPercentage calculates percentage of ratings', function () {
    $rateable = User::factory()->create();
    $users = User::factory()->count(4)->create();

    $users[0]->rate($rateable, 5);
    $users[1]->rate($rateable, 5);
    $users[2]->rate($rateable, 4);
    $users[3]->rate($rateable, 3);

    expect($rateable->ratingPercentage(5))->toBe(50.0);
    expect($rateable->ratingPercentage(4))->toBe(25.0);
    expect($rateable->ratingPercentage(3))->toBe(25.0);
    expect($rateable->ratingPercentage(1))->toBe(0.0);
});

test('ratingPercentage returns 0 when no ratings', function () {
    $user = User::factory()->create();

    expect($user->ratingPercentage(5))->toBe(0.0);
});

// ==========================================
// Rating Model Scopes
// ==========================================

test('withRating scope filters by specific rating value', function () {
    $user = User::factory()->create();
    $rateable = User::factory()->create();
    $users = User::factory()->count(3)->create();

    $users[0]->rate($rateable, 5);
    $users[1]->rate($rateable, 3);
    $users[2]->rate($rateable, 5);

    expect(Rating::withRating(5)->count())->toBe(2);
    expect(Rating::withRating(3)->count())->toBe(1);
    expect(Rating::withRating(1)->count())->toBe(0);
});

test('between scope filters ratings within range', function () {
    $rateable = User::factory()->create();
    $users = User::factory()->count(5)->create();

    $users[0]->rate($rateable, 1);
    $users[1]->rate($rateable, 2);
    $users[2]->rate($rateable, 3);
    $users[3]->rate($rateable, 4);
    $users[4]->rate($rateable, 5);

    expect(Rating::between(3, 5)->count())->toBe(3);
    expect(Rating::between(1, 2)->count())->toBe(2);
    expect(Rating::between(4, 5)->count())->toBe(2);
});

// ==========================================
// Soft Deletes
// ==========================================

test('ratings can be soft deleted', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $rating = $user->rate($otherUser, 4);
    $rating->delete();

    expect(Rating::count())->toBe(0);
    expect(Rating::withTrashed()->count())->toBe(1);
});

test('soft deleted ratings can be restored', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $rating = $user->rate($otherUser, 4);
    $rating->delete();
    $rating->restore();

    expect(Rating::count())->toBe(1);
});

// ==========================================
// Factory Tests
// ==========================================

test('rating factory works with by() and forRateable() methods', function () {
    $author = User::factory()->create();
    $rateable = User::factory()->create();

    $rating = Rating::factory()
        ->by($author)
        ->forRateable($rateable)
        ->withRating(4)
        ->create();

    expect($rating->rating)->toBe(4);
    expect($rating->author->id)->toBe($author->id);
    expect($rating->rateable->id)->toBe($rateable->id);
});
