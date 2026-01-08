<?php

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Location;

use function Pest\Laravel\{actingAs, get, assertDatabaseHas};

describe('Item Availability and Quantity Management Integration', function () {

    test('item availability calendar shows booked dates correctly', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 1,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Create approved booking
        $booking = Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'UserID' => $renter->UserID,
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(10),
            'Status' => 'Approved',
        ]);

        // Get unavailable dates API
        $response = actingAs($renter)->get("/api/items/{$item->ItemID}/unavailable-dates");

        $response->assertOk();
        $response->assertJson([
            'unavailable_dates' => [
                now()->addDays(5)->format('Y-m-d'),
                now()->addDays(6)->format('Y-m-d'),
                now()->addDays(7)->format('Y-m-d'),
                now()->addDays(8)->format('Y-m-d'),
                now()->addDays(9)->format('Y-m-d'),
                now()->addDays(10)->format('Y-m-d'),
            ]
        ]);
    });

    test('multi-quantity item shows partial availability', function () {
        $owner = User::factory()->create();
        $renter1 = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 3,
            'AvailableQuantity' => 3,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Book 2 quantities
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'UserID' => $renter1->UserID,
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(10),
            'Status' => 'Approved',
            'Quantity' => 2,
        ]);

        // Check availability
        $isAvailable = $item->isAvailableForDates(
            now()->addDays(5)->format('Y-m-d'),
            now()->addDays(10)->format('Y-m-d')
        );

        expect($isAvailable)->toBeTrue(); // Still 1 quantity available

        // Check booked quantity
        $bookedQty = $item->getBookedQuantity(
            now()->addDays(5)->format('Y-m-d'),
            now()->addDays(10)->format('Y-m-d')
        );

        expect($bookedQty)->toBe(2);
    });

    test('item becomes unavailable when all quantities booked', function () {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 2,
            'AvailableQuantity' => 2,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Book all quantities
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'UserID' => User::factory()->create()->UserID,
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(10),
            'Status' => 'Approved',
            'Quantity' => 2,
        ]);

        // Check availability
        $isAvailable = $item->isAvailableForDates(
            now()->addDays(5)->format('Y-m-d'),
            now()->addDays(10)->format('Y-m-d')
        );

        expect($isAvailable)->toBeFalse();
    });

    test('cancelled booking restores item availability', function () {
        $owner = User::factory()->create();
        $renter = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 1,
            'AvailableQuantity' => 0, // Initially unavailable
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $booking = Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'UserID' => $renter->UserID,
            'Status' => 'Approved',
            'Quantity' => 1,
        ]);

        // Cancel booking
        $booking->update(['Status' => 'Cancelled']);

        // Update item availability
        $item->updateAvailableQuantity();
        $item->refresh();

        expect($item->AvailableQuantity)->toBe(1);
        expect($item->Availability)->toBeTrue();
    });

    test('only confirmed and ongoing bookings affect availability', function () {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 3,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $dates = [
            'StartDate' => now()->addDays(5),
            'EndDate' => now()->addDays(10),
        ];

        // Pending booking - should NOT affect availability
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'UserID' => User::factory()->create()->UserID,
            'Status' => 'Pending',
            'Quantity' => 1,
            'StartDate' => $dates['StartDate'],
            'EndDate' => $dates['EndDate'],
        ]);

        // Approved booking - SHOULD affect availability
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'UserID' => User::factory()->create()->UserID,
            'Status' => 'Approved',
            'Quantity' => 1,
            'StartDate' => $dates['StartDate'],
            'EndDate' => $dates['EndDate'],
        ]);

        // Completed booking - should NOT affect future availability
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'UserID' => User::factory()->create()->UserID,
            'Status' => 'Completed',
            'Quantity' => 1,
            'StartDate' => $dates['StartDate'],
            'EndDate' => $dates['EndDate'],
        ]);

        // Count active bookings
        $bookedQty = $item->getBookedQuantity(
            $dates['StartDate']->format('Y-m-d'),
            $dates['EndDate']->format('Y-m-d')
        );

        expect($bookedQty)->toBe(1); // Only approved booking
    });

    test('item quantity update recalculates availability', function () {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 2,
            'AvailableQuantity' => 2,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Book 1 quantity
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'UserID' => User::factory()->create()->UserID,
            'Status' => 'Approved',
            'Quantity' => 1,
            'StartDate' => now()->addDays(1),
            'EndDate' => now()->addDays(30),
        ]);

        // Owner increases total quantity
        $item->update(['Quantity' => 5]);
        $item->updateAvailableQuantity();
        $item->refresh();

        expect($item->AvailableQuantity)->toBe(4); // 5 total - 1 booked
    });

    test('date overlap detection works correctly', function () {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 1,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Existing booking: Jan 10-15
        Booking::factory()->create([
            'ItemID' => $item->ItemID,
            'UserID' => User::factory()->create()->UserID,
            'StartDate' => '2025-01-10',
            'EndDate' => '2025-01-15',
            'Status' => 'Approved',
        ]);

        // Test various overlap scenarios
        // Scenario 1: Completely before (Jan 1-5) - Should be available
        $available1 = $item->isAvailableForDates('2025-01-01', '2025-01-05');
        expect($available1)->toBeTrue();

        // Scenario 2: Completely after (Jan 20-25) - Should be available
        $available2 = $item->isAvailableForDates('2025-01-20', '2025-01-25');
        expect($available2)->toBeTrue();

        // Scenario 3: Overlaps start (Jan 8-12) - Should NOT be available
        $available3 = $item->isAvailableForDates('2025-01-08', '2025-01-12');
        expect($available3)->toBeFalse();

        // Scenario 4: Overlaps end (Jan 13-18) - Should NOT be available
        $available4 = $item->isAvailableForDates('2025-01-13', '2025-01-18');
        expect($available4)->toBeFalse();

        // Scenario 5: Completely contains (Jan 9-16) - Should NOT be available
        $available5 = $item->isAvailableForDates('2025-01-09', '2025-01-16');
        expect($available5)->toBeFalse();

        // Scenario 6: Exact same dates (Jan 10-15) - Should NOT be available
        $available6 = $item->isAvailableForDates('2025-01-10', '2025-01-15');
        expect($available6)->toBeFalse();
    });

    test('available scope filters items correctly', function () {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        // Available item
        $item1 = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Availability' => true,
            'AvailableQuantity' => 2,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Unavailable item (disabled)
        $item2 = Item::factory()->unavailable()->create([
            'UserID' => $owner->UserID,
            'Availability' => false,
            'AvailableQuantity' => 0,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Query with available scope
        $availableItems = Item::available()->get();

        expect($availableItems->count())->toBe(1);
        expect($availableItems->first()->ItemID)->toBe($item1->ItemID);
    });

    test('concurrent bookings handle quantity correctly', function () {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'Quantity' => 5,
            'AvailableQuantity' => 5,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        $dates = [
            'start' => now()->addDays(5)->format('Y-m-d'),
            'end' => now()->addDays(10)->format('Y-m-d'),
        ];

        // Create 3 concurrent bookings
        for ($i = 0; $i < 3; $i++) {
            Booking::factory()->create([
                'ItemID' => $item->ItemID,
                'UserID' => User::factory()->create()->UserID,
                'StartDate' => $dates['start'],
                'EndDate' => $dates['end'],
                'Status' => 'Approved',
                'Quantity' => 1,
            ]);
        }

        // Check booked quantity
        $bookedQty = $item->getBookedQuantity($dates['start'], $dates['end']);
        expect($bookedQty)->toBe(3);

        // Check remaining availability
        $isAvailable = $item->isAvailableForDates($dates['start'], $dates['end']);
        expect($isAvailable)->toBeTrue(); // 5 total - 3 booked = 2 still available

        // Book remaining 2
        for ($i = 0; $i < 2; $i++) {
            Booking::factory()->create([
                'ItemID' => $item->ItemID,
                'UserID' => User::factory()->create()->UserID,
                'StartDate' => $dates['start'],
                'EndDate' => $dates['end'],
                'Status' => 'Approved',
                'Quantity' => 1,
            ]);
        }

        // Now should be fully booked
        $bookedQty = $item->getBookedQuantity($dates['start'], $dates['end']);
        expect($bookedQty)->toBe(5);

        $isAvailable = $item->isAvailableForDates($dates['start'], $dates['end']);
        expect($isAvailable)->toBeFalse();
    });
});
