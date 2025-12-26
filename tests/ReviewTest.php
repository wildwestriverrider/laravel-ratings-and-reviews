<?php

use Illuminate\Database\QueryException;
use Wildwestriverrider\LaravelRatingsAndReviews\Models\Review;
use Wildwestriverrider\LaravelRatingsAndReviews\Tests\User;

// ==========================================
// Basic Review Creation Tests
// ==========================================

test('users can create reviews', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $review = $user->reviewsWritten()->create([
        'reviewable_id' => $otherUser->id,
        'reviewable_type' => get_class($otherUser),
        'content' => 'review content',
    ]);

    expect($user->reviewsWritten()->first()->reviewable->id)->toBe($otherUser->id);
    expect($review->author->id)->toBe($user->id);
});

test('users can create one review per reviewable', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->reviewsWritten()->create([
        'reviewable_id' => $otherUser->id,
        'reviewable_type' => get_class($otherUser),
        'content' => 'review content',
    ]);

    expect(fn () => $user->reviewsWritten()->create([
        'reviewable_id' => $otherUser->id,
        'reviewable_type' => get_class($otherUser),
        'content' => 'review content',
    ]))->toThrow(QueryException::class);
});

test('reviews are unapproved by default', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $review = $user->review($otherUser, 'Great user!');

    expect($review->approved)->toBeFalse();
});

// ==========================================
// HasReviews Trait Helper Methods
// ==========================================

test('user can review a model using review() method', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $review = $user->review($otherUser, 'Great experience!');

    expect($review)->toBeInstanceOf(Review::class);
    expect($review->content)->toBe('Great experience!');
    expect($review->author->id)->toBe($user->id);
    expect($review->reviewable->id)->toBe($otherUser->id);
});

test('review() updates existing review instead of creating duplicate', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->review($otherUser, 'Initial review');
    $updatedReview = $user->review($otherUser, 'Updated review');

    expect($user->reviewsWritten()->count())->toBe(1);
    expect($updatedReview->content)->toBe('Updated review');
});

test('user can unreview a model', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->review($otherUser, 'A review');
    expect($user->reviewsWritten()->count())->toBe(1);

    $result = $user->unreview($otherUser);

    expect($result)->toBeTrue();
    expect($user->reviewsWritten()->count())->toBe(0);
});

test('hasReviewed returns true when user has reviewed', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->hasReviewed($otherUser))->toBeFalse();

    $user->review($otherUser, 'A review');

    expect($user->hasReviewed($otherUser))->toBeTrue();
});

test('getReviewFor returns the review for specific reviewable', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->getReviewFor($otherUser))->toBeNull();

    $user->review($otherUser, 'My review');

    $review = $user->getReviewFor($otherUser);
    expect($review)->toBeInstanceOf(Review::class);
    expect($review->content)->toBe('My review');
});

// ==========================================
// Review Approval Tests
// ==========================================

test('review can be approved', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $review = $user->review($otherUser, 'A review');
    expect($review->approved)->toBeFalse();

    $review->approve();

    expect($review->fresh()->approved)->toBeTrue();
});

test('review can be unapproved', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $review = $user->review($otherUser, 'A review');
    $review->approve();
    expect($review->fresh()->approved)->toBeTrue();

    $review->unapprove();

    expect($review->fresh()->approved)->toBeFalse();
});

// ==========================================
// Reviewable Trait Tests
// ==========================================

test('approvedReviews returns only approved reviews', function () {
    $reviewable = User::factory()->create();
    $users = User::factory()->count(3)->create();

    $review1 = $users[0]->review($reviewable, 'Review 1');
    $review2 = $users[1]->review($reviewable, 'Review 2');
    $review3 = $users[2]->review($reviewable, 'Review 3');

    $review1->approve();
    $review2->approve();

    expect($reviewable->approvedReviews()->count())->toBe(2);
    expect($reviewable->pendingReviews()->count())->toBe(1);
});

test('pendingReviews returns only unapproved reviews', function () {
    $reviewable = User::factory()->create();
    $user = User::factory()->create();

    $review = $user->review($reviewable, 'Pending review');

    expect($reviewable->pendingReviews()->count())->toBe(1);
    expect($reviewable->approvedReviews()->count())->toBe(0);
});

test('reviewCount returns total number of reviews', function () {
    $reviewable = User::factory()->create();
    $users = User::factory()->count(3)->create();

    expect($reviewable->reviewCount())->toBe(0);

    $users[0]->review($reviewable, 'Review 1');
    expect($reviewable->reviewCount())->toBe(1);

    $users[1]->review($reviewable, 'Review 2');
    $users[2]->review($reviewable, 'Review 3');
    expect($reviewable->reviewCount())->toBe(3);
});

test('approvedReviewCount returns only approved review count', function () {
    $reviewable = User::factory()->create();
    $users = User::factory()->count(3)->create();

    $review1 = $users[0]->review($reviewable, 'Review 1');
    $users[1]->review($reviewable, 'Review 2');
    $users[2]->review($reviewable, 'Review 3');

    expect($reviewable->approvedReviewCount())->toBe(0);

    $review1->approve();
    expect($reviewable->approvedReviewCount())->toBe(1);
});

test('reviewByUser returns review from specific user', function () {
    $reviewable = User::factory()->create();
    $user = User::factory()->create();

    expect($reviewable->reviewByUser($user))->toBeNull();

    $user->review($reviewable, 'User review');

    $review = $reviewable->reviewByUser($user);
    expect($review)->toBeInstanceOf(Review::class);
    expect($review->content)->toBe('User review');
});

test('isReviewedBy checks if specific user has reviewed', function () {
    $reviewable = User::factory()->create();
    $user = User::factory()->create();

    expect($reviewable->isReviewedBy($user))->toBeFalse();

    $user->review($reviewable, 'A review');

    expect($reviewable->isReviewedBy($user))->toBeTrue();
});

// ==========================================
// Review Model Scopes
// ==========================================

test('approved scope filters only approved reviews', function () {
    $reviewable = User::factory()->create();
    $users = User::factory()->count(3)->create();

    $review1 = $users[0]->review($reviewable, 'Review 1');
    $users[1]->review($reviewable, 'Review 2');
    $users[2]->review($reviewable, 'Review 3');

    $review1->approve();

    expect(Review::approved()->count())->toBe(1);
    expect(Review::pending()->count())->toBe(2);
});

test('pending scope filters only pending reviews', function () {
    $reviewable = User::factory()->create();
    $user = User::factory()->create();

    $review = $user->review($reviewable, 'Pending review');

    expect(Review::pending()->count())->toBe(1);

    $review->approve();

    expect(Review::pending()->count())->toBe(0);
});

// ==========================================
// Soft Deletes
// ==========================================

test('reviews can be soft deleted', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $review = $user->review($otherUser, 'A review');
    $review->delete();

    expect(Review::count())->toBe(0);
    expect(Review::withTrashed()->count())->toBe(1);
});

test('soft deleted reviews can be restored', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $review = $user->review($otherUser, 'A review');
    $review->delete();
    $review->restore();

    expect(Review::count())->toBe(1);
});

// ==========================================
// Factory Tests
// ==========================================

test('review factory works with by() and forReviewable() methods', function () {
    $author = User::factory()->create();
    $reviewable = User::factory()->create();

    $review = Review::factory()
        ->by($author)
        ->forReviewable($reviewable)
        ->withContent('Custom content')
        ->create();

    expect($review->content)->toBe('Custom content');
    expect($review->author->id)->toBe($author->id);
    expect($review->reviewable->id)->toBe($reviewable->id);
});

test('review factory approved state works', function () {
    $author = User::factory()->create();
    $reviewable = User::factory()->create();

    $review = Review::factory()
        ->by($author)
        ->forReviewable($reviewable)
        ->approved()
        ->create();

    expect($review->approved)->toBeTrue();
});

test('review factory pending state works', function () {
    $author = User::factory()->create();
    $reviewable = User::factory()->create();

    $review = Review::factory()
        ->by($author)
        ->forReviewable($reviewable)
        ->pending()
        ->create();

    expect($review->approved)->toBeFalse();
});
