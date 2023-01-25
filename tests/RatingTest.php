<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Wildwestriverrider\LaravelRatingsAndReviews\Tests\User;
use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertEquals;

test('users can create ratings', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $rating = $user->ratingsGiven()->create([
        'rateable_id' => $otherUser->id,
        'rateable_type' => get_class($otherUser),
        'rating' => 3
    ]);

    assertEquals($otherUser->toArray(), $user->ratingsGiven()->first()->rateable->toArray());
    assertEquals($rating->author->toArray(), $user->toArray());
});

test('users can create one rating per rateable', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $rating = $user->ratingsGiven()->create([
        'rateable_id' => $otherUser->id,
        'rateable_type' => get_class($otherUser),
        'rating' => 3
    ]);

    expect(fn() => $user->ratingsGiven()->create([
        'rateable_id' => $otherUser->id,
        'rateable_type' => get_class($otherUser),
        'rating' => 3
    ]))->toThrow(QueryException::class);
});

test('rateables have an average rating', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $anotherUser = User::factory()->create();


    $user->ratingsGiven()->create([
        'rateable_id' => $otherUser->id,
        'rateable_type' => get_class($otherUser),
        'rating' => 3
    ]);

    $anotherUser->ratingsGiven()->create([
        'rateable_id' => $otherUser->id,
        'rateable_type' => get_class($otherUser),
        'rating' => 1
    ]);


    expect($otherUser->averageRating())->toEqual(2);
});

test('users can only delete ratings they created', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $rating = $user->ratingsGiven()->create([
        'rateable_id' => $otherUser->id,
        'rateable_type' => get_class($otherUser),
        'rating' => 3
    ]);

    actingAs($otherUser);
    expect(fn() => $rating->delete())->toThrow(AuthorizationException::class);
});


