<?php

use Illuminate\Database\QueryException;
use Wildwestriverrider\LaravelRatingsAndReviews\Tests\User;
use function Pest\Laravel\assertDatabaseHas;
use function PHPUnit\Framework\assertEquals;

test('users can create reviews', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $review = $user->reviewsWritten()->create([
        'reviewable_id' => $otherUser->id,
        'reviewable_type' => get_class($otherUser),
        'content' => 'review content'
    ]);

    assertEquals($otherUser->toArray(), $user->reviewsWritten()->first()->reviewable->toArray());
    assertEquals($review->author->toArray(), $user->toArray());
});

test('users can create one review per reviewable', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $review = $user->reviewsWritten()->create([
        'reviewable_id' => $otherUser->id,
        'reviewable_type' => get_class($otherUser),
        'content' => 'review content'
    ]);


    expect(fn() => $user->reviewsWritten()->create([
        'reviewable_id' => $otherUser->id,
        'reviewable_type' => get_class($otherUser),
        'content' => 'review content'
    ]))->toThrow(QueryException::class);
});
