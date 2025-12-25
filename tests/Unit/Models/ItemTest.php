<?php

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\ItemImage;

test('item can be created with valid data', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();

    $item = Item::factory()->create([
        'UserID' => $user->UserID,
        'ItemName' => 'Test Item',
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
        'PricePerDay' => 50.00,
        'DepositAmount' => 100.00,
    ]);

    expect($item)->toBeInstanceOf(Item::class)
        ->and($item->ItemName)->toBe('Test Item')
        ->and($item->PricePerDay)->toBe('50.00')
        ->and($item->DepositAmount)->toBe('100.00')
        ->and($item->Availability)->toBeTrue();
});

test('item has default quantity and available quantity', function () {
    $item = Item::factory()->create([
        'Quantity' => 5,
    ]);

    expect($item->Quantity)->toBe(5)
        ->and($item->AvailableQuantity)->toBe(5);
});

test('item belongs to user', function () {
    $user = User::factory()->create();
    $item = Item::factory()->create(['UserID' => $user->UserID]);

    expect($item->user)->toBeInstanceOf(User::class)
        ->and($item->user->UserID)->toBe($user->UserID);
});

test('item belongs to category', function () {
    $category = Category::factory()->create();
    $item = Item::factory()->create(['CategoryID' => $category->CategoryID]);

    expect($item->category)->toBeInstanceOf(Category::class)
        ->and($item->category->CategoryID)->toBe($category->CategoryID);
});

test('item belongs to location', function () {
    $location = Location::factory()->create();
    $item = Item::factory()->create(['LocationID' => $location->LocationID]);

    expect($item->location)->toBeInstanceOf(Location::class)
        ->and($item->location->LocationID)->toBe($location->LocationID);
});

test('item has bookings relationship', function () {
    $item = Item::factory()->create();
    $booking = Booking::factory()->create(['ItemID' => $item->ItemID]);

    expect($item->bookings)->toHaveCount(1)
        ->and($item->bookings->first()->BookingID)->toBe($booking->BookingID);
});

test('item has reviews relationship', function () {
    $item = Item::factory()->create();
    $review = Review::factory()->create(['ItemID' => $item->ItemID]);

    expect($item->reviews)->toHaveCount(1)
        ->and($item->reviews->first()->ReviewID)->toBe($review->ReviewID);
});

test('item has wishlists relationship', function () {
    $item = Item::factory()->create();
    $user = User::factory()->create();
    $wishlist = Wishlist::factory()->create([
        'ItemID' => $item->ItemID,
        'UserID' => $user->UserID,
    ]);

    expect($item->wishlists)->toHaveCount(1)
        ->and($item->wishlists->first()->WishlistID)->toBe($wishlist->WishlistID);
});

test('item can check if in user wishlist', function () {
    $item = Item::factory()->create();
    $user = User::factory()->create();

    expect($item->isInWishlist($user->UserID))->toBeFalse();

    Wishlist::factory()->create([
        'ItemID' => $item->ItemID,
        'UserID' => $user->UserID,
    ]);

    expect($item->isInWishlist($user->UserID))->toBeTrue();
});

test('item has available quantity when available quantity is greater than zero', function () {
    $item = Item::factory()->create(['AvailableQuantity' => 3]);

    expect($item->hasAvailableQuantity())->toBeTrue();
});

test('item does not have available quantity when available quantity is zero', function () {
    $item = Item::factory()->create(['AvailableQuantity' => 0]);

    expect($item->hasAvailableQuantity())->toBeFalse();
});

test('item is available for dates when no overlapping bookings', function () {
    $item = Item::factory()->create(['Quantity' => 2]);
    $startDate = now()->addDays(5);
    $endDate = now()->addDays(10);

    expect($item->isAvailableForDates($startDate, $endDate))->toBeTrue();
});

test('item is not available when all quantities are booked', function () {
    $item = Item::factory()->create(['Quantity' => 1]);

    // Create a confirmed booking that overlaps
    Booking::factory()->create([
        'ItemID' => $item->ItemID,
        'StartDate' => now()->addDays(5),
        'EndDate' => now()->addDays(10),
        'Status' => 'Confirmed',
    ]);

    $startDate = now()->addDays(6);
    $endDate = now()->addDays(9);

    expect($item->isAvailableForDates($startDate, $endDate))->toBeFalse();
});

test('item is available when quantity allows for overlapping bookings', function () {
    $item = Item::factory()->create(['Quantity' => 2]);

    // Create one confirmed booking
    Booking::factory()->create([
        'ItemID' => $item->ItemID,
        'StartDate' => now()->addDays(5),
        'EndDate' => now()->addDays(10),
        'Status' => 'Confirmed',
    ]);

    $startDate = now()->addDays(6);
    $endDate = now()->addDays(9);

    // Should still be available since we have quantity of 2
    expect($item->isAvailableForDates($startDate, $endDate))->toBeTrue();
});

test('item can get booked quantity', function () {
    $item = Item::factory()->create(['Quantity' => 3]);

    // Create confirmed bookings that are currently active
    Booking::factory()->count(2)->create([
        'ItemID' => $item->ItemID,
        'StartDate' => now()->subDay(),
        'EndDate' => now()->addDay(),
        'Status' => 'Confirmed',
    ]);

    expect($item->getBookedQuantity())->toBe(2);
});

test('item can update available quantity based on active bookings', function () {
    $item = Item::factory()->create(['Quantity' => 5, 'AvailableQuantity' => 5]);

    // Create 2 active confirmed bookings
    Booking::factory()->count(2)->create([
        'ItemID' => $item->ItemID,
        'StartDate' => now()->subDay(),
        'EndDate' => now()->addDay(),
        'Status' => 'Confirmed',
    ]);

    $availableQuantity = $item->updateAvailableQuantity();

    expect($availableQuantity)->toBe(3);

    $item->refresh();
    expect($item->AvailableQuantity)->toBe(3)
        ->and($item->Availability)->toBeTrue();
});

test('item availability becomes false when available quantity is zero', function () {
    $item = Item::factory()->create(['Quantity' => 1, 'AvailableQuantity' => 1]);

    // Create active booking to book all quantity
    Booking::factory()->create([
        'ItemID' => $item->ItemID,
        'StartDate' => now()->subDay(),
        'EndDate' => now()->addDay(),
        'Status' => 'Confirmed',
    ]);

    $item->updateAvailableQuantity();
    $item->refresh();

    expect($item->AvailableQuantity)->toBe(0)
        ->and($item->Availability)->toBeFalse();
});

test('item can get average rating from reviews', function () {
    $item = Item::factory()->create();

    Review::factory()->create(['ItemID' => $item->ItemID, 'Rating' => 5]);
    Review::factory()->create(['ItemID' => $item->ItemID, 'Rating' => 4]);
    Review::factory()->create(['ItemID' => $item->ItemID, 'Rating' => 3]);

    $averageRating = $item->getAverageRatingAttribute();

    expect((float) $averageRating)->toBe(4.0);
});

test('item returns zero average rating when no reviews', function () {
    $item = Item::factory()->create();

    expect($item->getAverageRatingAttribute())->toBe(0);
});

test('item can get total reviews count', function () {
    $item = Item::factory()->create();
    Review::factory()->count(3)->create(['ItemID' => $item->ItemID]);

    expect($item->getTotalReviewsAttribute())->toBe(3);
});

test('item available scope filters available items', function () {
    Item::factory()->create(['Availability' => true, 'AvailableQuantity' => 2]);
    Item::factory()->create(['Availability' => false, 'AvailableQuantity' => 0]);
    Item::factory()->create(['Availability' => true, 'AvailableQuantity' => 1]);

    $availableItems = Item::available()->get();

    expect($availableItems)->toHaveCount(2);
});

test('item by category scope filters items by category', function () {
    $category = Category::factory()->create();
    Item::factory()->count(2)->create(['CategoryID' => $category->CategoryID]);
    Item::factory()->create(); // Different category

    $categoryItems = Item::byCategory($category->CategoryID)->get();

    expect($categoryItems)->toHaveCount(2);
});

test('item by location scope filters items by location', function () {
    $location = Location::factory()->create();
    Item::factory()->count(3)->create(['LocationID' => $location->LocationID]);
    Item::factory()->create(); // Different location

    $locationItems = Item::byLocation($location->LocationID)->get();

    expect($locationItems)->toHaveCount(3);
});
