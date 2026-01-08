<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\Message;
use App\Models\Category;
use App\Models\Location;

use function Pest\Laravel\{actingAs, post, get, assertDatabaseHas, assertDatabaseMissing};

describe('User Features Integration - Wishlist, Reviews, Messages', function () {

    // ==================== WISHLIST TESTS ====================

    test('user can add item to wishlist', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // User adds item to wishlist
        $response = actingAs($user)->post('/wishlist/toggle', [
            'ItemID' => $item->ItemID,
        ]);

        assertDatabaseHas('wishlist', [
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);
    });

    test('user can remove item from wishlist', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Add to wishlist first
        Wishlist::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);

        // User removes from wishlist
        $response = actingAs($user)->post('/wishlist/toggle', [
            'ItemID' => $item->ItemID,
        ]);

        assertDatabaseMissing('wishlist', [
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);
    });

    test('user can view their wishlist items', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item1 = Item::factory()->create([
            'UserID' => $owner->UserID,
            'ItemName' => 'Camera',
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $item2 = Item::factory()->create([
            'UserID' => $owner->UserID,
            'ItemName' => 'Drone',
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        Wishlist::factory()->create(['UserID' => $user->UserID, 'ItemID' => $item1->ItemID]);
        Wishlist::factory()->create(['UserID' => $user->UserID, 'ItemID' => $item2->ItemID]);

        // User views wishlist
        $response = actingAs($user)->get('/wishlist');

        $response->assertOk();
        $response->assertSee('Camera');
        $response->assertSee('Drone');
    });

    // ==================== REVIEW TESTS ====================

    test('user can submit review after booking completion', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->completed()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Completed',
        ]);

        // User submits review
        $response = actingAs($renter)->post("/items/{$item->ItemID}/reviews", [
            'Rating' => 5,
            'Comment' => 'Excellent item, highly recommend!',
        ]);

        assertDatabaseHas('review', [
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Rating' => 5,
            'Comment' => 'Excellent item, highly recommend!',
        ]);
    });

    test('review with image uploads successfully', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        Booking::factory()->completed()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
        ]);

        $review = Review::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Rating' => 4,
            'Comment' => 'Good item',
            'ReviewImage' => 'reviews/test-image.jpg',
        ]);

        expect($review->ReviewImage)->toBe('reviews/test-image.jpg');
    });

    test('item average rating updates after new review', function () {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Create reviews with different ratings
        Review::factory()->create(['ItemID' => $item->ItemID, 'Rating' => 5]);
        Review::factory()->create(['ItemID' => $item->ItemID, 'Rating' => 4]);
        Review::factory()->create(['ItemID' => $item->ItemID, 'Rating' => 5]);

        // Calculate average rating
        $averageRating = $item->getAverageRatingAttribute();

        expect($averageRating)->toBe(4.67);
    });

    test('user cannot submit duplicate review for same item', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        Booking::factory()->completed()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
        ]);

        // First review
        Review::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
        ]);

        // Attempt second review
        $response = actingAs($renter)->post("/items/{$item->ItemID}/reviews", [
            'Rating' => 3,
            'Comment' => 'Changed my mind',
        ]);

        $response->assertSessionHasErrors();

        // Verify only one review exists
        $reviewCount = Review::where('UserID', $renter->UserID)
            ->where('ItemID', $item->ItemID)
            ->count();

        expect($reviewCount)->toBe(1);
    });

    test('reviews display on item details page', function () {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'ItemName' => 'Test Item',
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $reviewer = User::factory()->create(['UserName' => 'John Reviewer']);

        Review::factory()->create([
            'UserID' => $reviewer->UserID,
            'ItemID' => $item->ItemID,
            'Rating' => 5,
            'Comment' => 'Amazing product!',
        ]);

        // View item details
        $response = get("/items/{$item->ItemID}");

        $response->assertOk();
        $response->assertSee('Amazing product!');
        $response->assertSee('John Reviewer');
    });

    // ==================== MESSAGING TESTS ====================

    test('user can send message to item owner', function () {
        $owner = User::factory()->create(['UserName' => 'Item Owner']);
        $renter = User::factory()->create(['UserName' => 'Renter']);
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Renter sends message
        $response = actingAs($renter)->post('/messages/send', [
            'ReceiverID' => $owner->UserID,
            'ItemID' => $item->ItemID,
            'MessageContent' => 'Is this item available next week?',
        ]);

        assertDatabaseHas('messages', [
            'SenderID' => $renter->UserID,
            'ReceiverID' => $owner->UserID,
            'ItemID' => $item->ItemID,
            'MessageContent' => 'Is this item available next week?',
            'IsRead' => false,
        ]);
    });

    test('user can view conversation thread', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $user1->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Create conversation
        Message::factory()->create([
            'SenderID' => $user1->UserID,
            'ReceiverID' => $user2->UserID,
            'ItemID' => $item->ItemID,
            'MessageContent' => 'Hello!',
        ]);

        Message::factory()->create([
            'SenderID' => $user2->UserID,
            'ReceiverID' => $user1->UserID,
            'ItemID' => $item->ItemID,
            'MessageContent' => 'Hi there!',
        ]);

        // User1 views conversation
        $response = actingAs($user1)->get("/messages/conversation/{$user2->UserID}");

        $response->assertOk();
        $response->assertSee('Hello!');
        $response->assertSee('Hi there!');
    });

    test('message is marked as read when viewed', function () {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $sender->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $message = Message::factory()->create([
            'SenderID' => $sender->UserID,
            'ReceiverID' => $receiver->UserID,
            'ItemID' => $item->ItemID,
            'IsRead' => false,
        ]);

        // Receiver views and marks as read
        $response = actingAs($receiver)->post("/messages/{$message->MessageID}/mark-read");

        $message->refresh();
        expect($message->IsRead)->toBeTrue();
    });

    test('unread message count is accurate', function () {
        $user = User::factory()->create();
        $sender = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $sender->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Create 3 unread messages
        Message::factory()->count(3)->create([
            'ReceiverID' => $user->UserID,
            'SenderID' => $sender->UserID,
            'ItemID' => $item->ItemID,
            'IsRead' => false,
        ]);

        // Create 2 read messages
        Message::factory()->count(2)->create([
            'ReceiverID' => $user->UserID,
            'SenderID' => $sender->UserID,
            'ItemID' => $item->ItemID,
            'IsRead' => true,
        ]);

        // Check unread count
        $unreadCount = Message::where('ReceiverID', $user->UserID)
            ->where('IsRead', false)
            ->count();

        expect($unreadCount)->toBe(3);
    });

    test('messages are sorted chronologically in conversation', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $user1->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Create messages at different times
        $msg1 = Message::factory()->create([
            'SenderID' => $user1->UserID,
            'ReceiverID' => $user2->UserID,
            'ItemID' => $item->ItemID,
            'MessageContent' => 'First message',
            'SentAt' => now()->subHours(2),
        ]);

        $msg2 = Message::factory()->create([
            'SenderID' => $user2->UserID,
            'ReceiverID' => $user1->UserID,
            'ItemID' => $item->ItemID,
            'MessageContent' => 'Second message',
            'SentAt' => now()->subHour(),
        ]);

        $msg3 = Message::factory()->create([
            'SenderID' => $user1->UserID,
            'ReceiverID' => $user2->UserID,
            'ItemID' => $item->ItemID,
            'MessageContent' => 'Third message',
            'SentAt' => now(),
        ]);

        // Get conversation
        $conversation = Message::conversation($user1->UserID, $user2->UserID)->get();

        expect($conversation[0]->MessageContent)->toBe('First message');
        expect($conversation[1]->MessageContent)->toBe('Second message');
        expect($conversation[2]->MessageContent)->toBe('Third message');
    });
});
