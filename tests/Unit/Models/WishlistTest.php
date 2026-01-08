<?php

use App\Models\Wishlist;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Location;

// UT-WISH-001: Create wishlist with valid data
test('wishlist can be created with valid data', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    $wishlist = Wishlist::create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
        'DateAdded' => now(),
    ]);

    expect($wishlist)->toBeInstanceOf(Wishlist::class)
        ->and($wishlist->UserID)->toBe($user->UserID)
        ->and($wishlist->ItemID)->toBe($item->ItemID)
        ->and($wishlist->DateAdded)->not->toBeNull();
});

// UT-WISH-002: Verify default DateAdded is set automatically
test('wishlist automatically sets DateAdded on creation', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    $wishlist = Wishlist::create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
    ]);

    expect($wishlist->DateAdded)->not->toBeNull()
        ->and($wishlist->DateAdded)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

// UT-WISH-003: Verify wishlist belongs to user relationship
test('wishlist belongs to user', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    $wishlist = Wishlist::create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
    ]);

    expect($wishlist->user)->toBeInstanceOf(User::class)
        ->and($wishlist->user->UserID)->toBe($user->UserID);
});

// UT-WISH-004: Verify wishlist belongs to item relationship
test('wishlist belongs to item', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    $wishlist = Wishlist::create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
    ]);

    expect($wishlist->item)->toBeInstanceOf(Item::class)
        ->and($wishlist->item->ItemID)->toBe($item->ItemID);
});

// UT-WISH-005: Verify forUser scope filters by user
test('forUser scope filters wishlist by user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();

    $item1 = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);
    $item2 = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    Wishlist::create(['UserID' => $user1->UserID, 'ItemID' => $item1->ItemID]);
    Wishlist::create(['UserID' => $user2->UserID, 'ItemID' => $item2->ItemID]);

    $user1Wishlist = Wishlist::forUser($user1->UserID)->get();

    expect($user1Wishlist)->toHaveCount(1)
        ->and($user1Wishlist->first()->UserID)->toBe($user1->UserID);
});

// UT-WISH-006: Check if item is in user's wishlist (exists)
test('isInWishlist returns true when item is in wishlist', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    Wishlist::create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
    ]);

    expect(Wishlist::isInWishlist($user->UserID, $item->ItemID))->toBeTrue();
});

// UT-WISH-007: Check if item is in user's wishlist (does not exist)
test('isInWishlist returns false when item is not in wishlist', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    expect(Wishlist::isInWishlist($user->UserID, $item->ItemID))->toBeFalse();
});

// UT-WISH-008: Toggle wishlist - add item
test('toggle adds item to wishlist when not exists', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    $result = Wishlist::toggle($user->UserID, $item->ItemID);

    expect($result['added'])->toBeTrue()
        ->and($result['message'])->toBe('Added to wishlist')
        ->and(Wishlist::isInWishlist($user->UserID, $item->ItemID))->toBeTrue();
});

// UT-WISH-009: Toggle wishlist - remove item
test('toggle removes item from wishlist when exists', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    // First add to wishlist
    Wishlist::create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
    ]);

    // Then toggle to remove
    $result = Wishlist::toggle($user->UserID, $item->ItemID);

    expect($result['added'])->toBeFalse()
        ->and($result['message'])->toBe('Removed from wishlist')
        ->and(Wishlist::isInWishlist($user->UserID, $item->ItemID))->toBeFalse();
});

// UT-WISH-010: Toggle wishlist multiple times
test('toggle works correctly when called multiple times', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    // First toggle - add
    $result1 = Wishlist::toggle($user->UserID, $item->ItemID);
    expect($result1['added'])->toBeTrue();

    // Second toggle - remove
    $result2 = Wishlist::toggle($user->UserID, $item->ItemID);
    expect($result2['added'])->toBeFalse();

    // Third toggle - add again
    $result3 = Wishlist::toggle($user->UserID, $item->ItemID);
    expect($result3['added'])->toBeTrue();
});

// UT-WISH-011: Multiple users can wishlist same item
test('multiple users can add same item to their wishlists', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    Wishlist::create(['UserID' => $user1->UserID, 'ItemID' => $item->ItemID]);
    Wishlist::create(['UserID' => $user2->UserID, 'ItemID' => $item->ItemID]);

    expect(Wishlist::isInWishlist($user1->UserID, $item->ItemID))->toBeTrue()
        ->and(Wishlist::isInWishlist($user2->UserID, $item->ItemID))->toBeTrue()
        ->and(Wishlist::where('ItemID', $item->ItemID)->count())->toBe(2);
});

// UT-WISH-012: User can wishlist multiple items
test('user can add multiple items to wishlist', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();

    $item1 = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);
    $item2 = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);
    $item3 = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    Wishlist::create(['UserID' => $user->UserID, 'ItemID' => $item1->ItemID]);
    Wishlist::create(['UserID' => $user->UserID, 'ItemID' => $item2->ItemID]);
    Wishlist::create(['UserID' => $user->UserID, 'ItemID' => $item3->ItemID]);

    $userWishlist = Wishlist::forUser($user->UserID)->get();

    expect($userWishlist)->toHaveCount(3);
});

// UT-WISH-013: Wishlist deletion works correctly
test('wishlist item can be deleted', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    $wishlist = Wishlist::create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
    ]);

    $wishlistId = $wishlist->WishlistID;
    $wishlist->delete();

    expect(Wishlist::find($wishlistId))->toBeNull()
        ->and(Wishlist::isInWishlist($user->UserID, $item->ItemID))->toBeFalse();
});

// UT-WISH-014: DateAdded field is cast to datetime
test('DateAdded field is cast to Carbon datetime', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $location = Location::factory()->create();
    $item = Item::factory()->create([
        'CategoryID' => $category->CategoryID,
        'LocationID' => $location->LocationID,
    ]);

    $wishlist = Wishlist::create([
        'UserID' => $user->UserID,
        'ItemID' => $item->ItemID,
    ]);

    expect($wishlist->DateAdded)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

// UT-WISH-015: Wishlist uses correct table name
test('wishlist uses correct table name', function () {
    $wishlist = new Wishlist();

    expect($wishlist->getTable())->toBe('wishlist');
});

// UT-WISH-016: Wishlist uses correct primary key
test('wishlist uses correct primary key', function () {
    $wishlist = new Wishlist();

    expect($wishlist->getKeyName())->toBe('WishlistID');
});

// UT-WISH-017: Wishlist timestamps are disabled
test('wishlist has timestamps disabled', function () {
    $wishlist = new Wishlist();

    expect($wishlist->timestamps)->toBeFalse();
});

// UT-WISH-018: Wishlist fillable fields are correct
test('wishlist has correct fillable fields', function () {
    $wishlist = new Wishlist();

    expect($wishlist->getFillable())->toContain('UserID')
        ->and($wishlist->getFillable())->toContain('ItemID')
        ->and($wishlist->getFillable())->toContain('DateAdded');
});
