<?php

use App\Models\Review;
use App\Models\User;
use App\Models\Item;

test('review can be created with valid data', function () {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $review = Review::factory()->create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
        'Rating' => 5,
        'Comment' => 'Great item!',
    ]);

    expect($review)->toBeInstanceOf(Review::class)
        ->and($review->UserID)->toBe($user->UserID)
        ->and($review->ItemID)->toBe($item->ItemID)
        ->and($review->Rating)->toBe(5)
        ->and($review->Comment)->toBe('Great item!')
        ->and($review->IsReported)->toBeFalse();
});

test('review rating must be between 1 and 5', function () {
    $review = Review::factory()->create(['Rating' => 3]);

    expect($review->Rating)->toBeGreaterThanOrEqual(1)
        ->and($review->Rating)->toBeLessThanOrEqual(5);
});

test('review belongs to user', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create(['UserID' => $user->UserID]);

    expect($review->user)->toBeInstanceOf(User::class)
        ->and($review->user->UserID)->toBe($user->UserID);
});

test('review belongs to item', function () {
    $item = Item::factory()->create();
    $review = Review::factory()->create(['ItemID' => $item->ItemID]);

    expect($review->item)->toBeInstanceOf(Item::class)
        ->and($review->item->ItemID)->toBe($item->ItemID);
});

test('review not reported scope filters non-reported reviews', function () {
    Review::factory()->create(['IsReported' => false]);
    Review::factory()->create(['IsReported' => true]);
    Review::factory()->create(['IsReported' => false]);

    $notReportedReviews = Review::notReported()->get();

    expect($notReportedReviews)->toHaveCount(2);
});

test('review recent scope orders by most recent', function () {
    $review1 = Review::factory()->create(['DatePosted' => now()->subDays(3)]);
    $review2 = Review::factory()->create(['DatePosted' => now()->subDay()]);
    $review3 = Review::factory()->create(['DatePosted' => now()]);

    $recentReviews = Review::recent()->get();

    expect($recentReviews->first()->ReviewID)->toBe($review3->ReviewID)
        ->and($recentReviews->last()->ReviewID)->toBe($review1->ReviewID);
});

test('review can have an image', function () {
    $review = Review::factory()->withImage()->create();

    expect($review->ReviewImage)->not->toBeNull();
});

test('review date posted is cast to datetime', function () {
    $review = Review::factory()->create();

    expect($review->DatePosted)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('review rating is cast to integer', function () {
    $review = Review::factory()->create(['Rating' => 4]);

    expect($review->Rating)->toBeInt();
});
