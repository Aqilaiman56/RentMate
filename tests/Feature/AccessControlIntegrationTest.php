<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Location;

use function Pest\Laravel\{actingAs, get, post, delete};

describe('Access Control and Authorization Integration', function () {

    test('guest cannot access authenticated routes', function () {
        // Attempt to access booking page without authentication
        $response = get('/bookings');

        $response->assertRedirect('/login');
    });

    test('authenticated user can access their own bookings', function () {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        Booking::factory()->create([
            'UserID' => $user->UserID,
            'ItemID' => $item->ItemID,
        ]);

        // User accesses their bookings
        $response = actingAs($user)->get('/bookings');

        $response->assertOk();
    });

    test('user cannot view other users bookings', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => User::factory()->create()->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'UserID' => $user2->UserID,
            'ItemID' => $item->ItemID,
        ]);

        // User1 attempts to view User2's booking
        $response = actingAs($user1)->get("/booking/{$booking->BookingID}");

        $response->assertForbidden();
    });

    test('item owner can approve their own item bookings', function () {
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
        $response = actingAs($owner)->post("/booking/{$booking->BookingID}/approve");

        $response->assertRedirect();

        $booking->refresh();
        expect($booking->Status)->toBe('Approved');
    });

    test('non-owner cannot approve bookings for others items', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $otherUser = User::factory()->create();
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

        // Other user attempts to approve
        $response = actingAs($otherUser)->post("/booking/{$booking->BookingID}/approve");

        $response->assertForbidden();
    });

    test('user can only edit their own items', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Owner can edit
        $response = actingAs($owner)->get("/items/{$item->ItemID}/edit");
        $response->assertOk();

        // Other user cannot edit
        $response = actingAs($otherUser)->get("/items/{$item->ItemID}/edit");
        $response->assertForbidden();
    });

    test('user can only delete their own items', function () {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Other user attempts to delete
        $response = actingAs($otherUser)->delete("/items/{$item->ItemID}");

        $response->assertForbidden();
    });

    test('non-admin cannot access admin dashboard', function () {
        $regularUser = User::factory()->create(['IsAdmin' => false]);

        $response = actingAs($regularUser)->get('/admin');

        $response->assertForbidden();
    });

    test('admin can access admin dashboard', function () {
        $admin = User::factory()->admin()->create();

        $response = actingAs($admin)->get('/admin');

        $response->assertOk();
    });

    test('non-admin cannot access admin user management', function () {
        $regularUser = User::factory()->create(['IsAdmin' => false]);

        $response = actingAs($regularUser)->get('/admin/users');

        $response->assertForbidden();
    });

    test('non-admin cannot suspend users', function () {
        $regularUser = User::factory()->create(['IsAdmin' => false]);
        $targetUser = User::factory()->create();

        $response = actingAs($regularUser)->post("/admin/users/{$targetUser->UserID}/suspend", [
            'suspension_reason' => 'Test',
            'suspended_until' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertForbidden();
    });

    test('suspended user cannot access authenticated routes', function () {
        $suspendedUser = User::factory()->suspended()->create([
            'IsSuspended' => true,
            'SuspendedUntil' => now()->addDays(7),
            'SuspensionReason' => 'Policy violation',
        ]);

        // Attempt to access bookings
        $response = actingAs($suspendedUser)->get('/bookings');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    });

    test('expired suspension allows user access', function () {
        $user = User::factory()->create([
            'IsSuspended' => true,
            'SuspendedUntil' => now()->subDay(), // Expired
        ]);

        // User should be auto-unsuspended
        expect($user->isCurrentlySuspended())->toBeFalse();

        // User can access routes
        $response = actingAs($user)->get('/dashboard');

        $response->assertOk();
    });

    test('admin can access suspended user routes', function () {
        $admin = User::factory()->create([
            'IsAdmin' => true,
            'IsSuspended' => true,
        ]);

        // Admin bypass suspension check
        $response = actingAs($admin)->get('/admin');

        $response->assertOk();
    });

    test('user can view public item details without authentication', function () {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Guest views item
        $response = get("/items/{$item->ItemID}");

        $response->assertOk();
    });

    test('authenticated user sees booking button on item details', function () {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Authenticated user views item
        $response = actingAs($user)->get("/items/{$item->ItemID}");

        $response->assertOk();
        $response->assertSee('Book Now'); // Or similar booking button text
    });

    test('user can view their own profile', function () {
        $user = User::factory()->create();

        $response = actingAs($user)->get('/profile');

        $response->assertOk();
    });

    test('user can edit their own profile', function () {
        $user = User::factory()->create();

        $response = actingAs($user)->post('/profile/update', [
            'UserName' => 'Updated Name',
            'PhoneNumber' => '0123456789',
            'Location' => 'Kuala Lumpur',
        ]);

        $response->assertRedirect();

        $user->refresh();
        expect($user->UserName)->toBe('Updated Name');
    });

    test('user cannot edit other users profiles', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = actingAs($user1)->post("/profile/{$user2->UserID}/update", [
            'UserName' => 'Hacked Name',
        ]);

        $response->assertForbidden();
    });

    test('user can cancel their own pending bookings', function () {
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

        // Renter cancels their booking
        $response = actingAs($renter)->post("/booking/{$booking->BookingID}/cancel");

        $response->assertRedirect();

        $booking->refresh();
        expect($booking->Status)->toBe('Cancelled');
    });

    test('user cannot cancel other users bookings', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $otherUser = User::factory()->create();
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

        // Other user attempts to cancel
        $response = actingAs($otherUser)->post("/booking/{$booking->BookingID}/cancel");

        $response->assertForbidden();
    });

    test('middleware protects routes requiring email verification', function () {
        $unverifiedUser = User::factory()->unverified()->create();

        // Attempt to access route requiring verification
        $response = actingAs($unverifiedUser)->get('/bookings');

        $response->assertRedirect('/verify-email');
    });

    test('verified user can access routes requiring verification', function () {
        $verifiedUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = actingAs($verifiedUser)->get('/bookings');

        $response->assertOk();
    });
});
