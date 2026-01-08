<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Category;
use App\Models\Location;

use function Pest\Laravel\{actingAs, post, get, assertDatabaseHas};

describe('Notification System Integration', function () {

    test('notification is created when booking is created', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Pending',
        ]);

        // Create notification for owner
        Notification::create([
            'UserID' => $owner->UserID,
            'Type' => 'booking',
            'Title' => 'New Booking Request',
            'Content' => 'You have a new booking request for ' . $item->ItemName,
            'RelatedID' => $booking->BookingID,
            'RelatedType' => 'booking',
            'IsRead' => false,
        ]);

        assertDatabaseHas('notifications', [
            'UserID' => $owner->UserID,
            'Type' => 'booking',
            'RelatedID' => $booking->BookingID,
            'IsRead' => false,
        ]);
    });

    test('notification is created when booking is approved', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Pending',
        ]);

        // Owner approves booking
        actingAs($owner)->post("/booking/{$booking->BookingID}/approve");

        // Verify notification sent to renter
        assertDatabaseHas('notifications', [
            'UserID' => $renter->UserID,
            'Type' => 'booking',
            'RelatedID' => $booking->BookingID,
        ]);
    });

    test('notification is created when booking is rejected', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
            'Status' => 'Pending',
        ]);

        // Owner rejects booking
        actingAs($owner)->post("/booking/{$booking->BookingID}/reject", [
            'rejection_reason' => 'Item no longer available'
        ]);

        // Verify notification sent to renter
        assertDatabaseHas('notifications', [
            'UserID' => $renter->UserID,
            'Type' => 'booking',
        ]);
    });

    test('notification is created when payment is successful', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
        ]);

        // Create payment success notification
        Notification::create([
            'UserID' => $renter->UserID,
            'Type' => 'payment',
            'Title' => 'Payment Successful',
            'Content' => 'Your payment for booking #' . $booking->BookingID . ' was successful',
            'RelatedID' => $booking->BookingID,
            'RelatedType' => 'payment',
            'IsRead' => false,
        ]);

        assertDatabaseHas('notifications', [
            'UserID' => $renter->UserID,
            'Type' => 'payment',
            'Title' => 'Payment Successful',
        ]);
    });

    test('user can view all their notifications', function () {
        $user = User::factory()->create();

        Notification::factory()->count(5)->create([
            'UserID' => $user->UserID,
            'IsRead' => false,
        ]);

        Notification::factory()->count(3)->create([
            'UserID' => $user->UserID,
            'IsRead' => true,
        ]);

        // User views notifications
        $response = actingAs($user)->get('/notifications');

        $response->assertOk();

        $allNotifications = Notification::where('UserID', $user->UserID)->get();
        expect($allNotifications->count())->toBe(8);
    });

    test('user can mark notification as read', function () {
        $user = User::factory()->create();

        $notification = Notification::factory()->create([
            'UserID' => $user->UserID,
            'IsRead' => false,
        ]);

        // User marks as read
        $response = actingAs($user)->post("/notifications/{$notification->NotificationID}/read");

        $notification->refresh();
        expect($notification->IsRead)->toBeTrue();
    });

    test('user can mark all notifications as read', function () {
        $user = User::factory()->create();

        Notification::factory()->count(5)->create([
            'UserID' => $user->UserID,
            'IsRead' => false,
        ]);

        // User marks all as read
        $response = actingAs($user)->post('/notifications/mark-all-read');

        $unreadCount = Notification::where('UserID', $user->UserID)
            ->where('IsRead', false)
            ->count();

        expect($unreadCount)->toBe(0);
    });

    test('user can get unread notification count', function () {
        $user = User::factory()->create();

        Notification::factory()->count(7)->create([
            'UserID' => $user->UserID,
            'IsRead' => false,
        ]);

        Notification::factory()->count(3)->create([
            'UserID' => $user->UserID,
            'IsRead' => true,
        ]);

        // Get unread count
        $response = actingAs($user)->get('/notifications/unread-count');

        $response->assertOk();
        $response->assertJson(['count' => 7]);
    });

    test('user can clear all notifications', function () {
        $user = User::factory()->create();

        Notification::factory()->count(10)->create([
            'UserID' => $user->UserID,
        ]);

        // User clears all notifications
        $response = actingAs($user)->post('/notifications/clear');

        $remainingCount = Notification::where('UserID', $user->UserID)->count();
        expect($remainingCount)->toBe(0);
    });

    test('notifications are sorted by creation date descending', function () {
        $user = User::factory()->create();

        $notif1 = Notification::factory()->create([
            'UserID' => $user->UserID,
            'Title' => 'First',
            'CreatedAt' => now()->subDays(3),
        ]);

        $notif2 = Notification::factory()->create([
            'UserID' => $user->UserID,
            'Title' => 'Second',
            'CreatedAt' => now()->subDays(2),
        ]);

        $notif3 = Notification::factory()->create([
            'UserID' => $user->UserID,
            'Title' => 'Third',
            'CreatedAt' => now()->subDay(),
        ]);

        // Get notifications
        $notifications = Notification::where('UserID', $user->UserID)
            ->orderBy('CreatedAt', 'desc')
            ->get();

        expect($notifications[0]->Title)->toBe('Third');
        expect($notifications[1]->Title)->toBe('Second');
        expect($notifications[2]->Title)->toBe('First');
    });

    test('user only sees their own notifications', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Notification::factory()->count(5)->create(['UserID' => $user1->UserID]);
        Notification::factory()->count(3)->create(['UserID' => $user2->UserID]);

        // User1 views notifications
        $response = actingAs($user1)->get('/notifications');

        $response->assertOk();

        $user1Notifications = Notification::where('UserID', $user1->UserID)->get();
        expect($user1Notifications->count())->toBe(5);
    });

    test('notification includes related entity information', function () {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);

        $notification = Notification::factory()->create([
            'UserID' => $user->UserID,
            'Type' => 'booking',
            'Title' => 'Booking Confirmed',
            'Content' => 'Your booking has been confirmed',
            'RelatedID' => $booking->BookingID,
            'RelatedType' => 'booking',
        ]);

        expect($notification->RelatedID)->toBe($booking->BookingID);
        expect($notification->RelatedType)->toBe('booking');
    });

    test('admin suspension notification is sent to user', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        // Admin suspends user (this should trigger notification)
        actingAs($admin)->post("/admin/users/{$user->UserID}/suspend", [
            'suspension_reason' => 'Policy violation',
            'suspended_until' => now()->addDays(7)->format('Y-m-d'),
        ]);

        // Verify notification created
        assertDatabaseHas('notifications', [
            'UserID' => $user->UserID,
            'Type' => 'admin',
        ]);
    });

    test('notification badge shows correct unread count', function () {
        $user = User::factory()->create();

        Notification::factory()->count(12)->create([
            'UserID' => $user->UserID,
            'IsRead' => false,
        ]);

        // Get unread count via scope
        $unreadCount = Notification::where('UserID', $user->UserID)
            ->unread()
            ->count();

        expect($unreadCount)->toBe(12);
    });
});
