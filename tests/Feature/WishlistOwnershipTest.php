<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WishlistOwnershipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that item owners cannot add their own items to wishlist
     */
    public function test_item_owner_cannot_add_own_item_to_wishlist()
    {
        // Create a user (item owner)
        $owner = User::factory()->create();

        // Create necessary dependencies
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        // Create an item owned by the user
        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Act as the owner and try to add their own item to wishlist
        $response = $this->actingAs($owner)->postJson("/wishlist/toggle/{$item->ItemID}");

        // Assert that the request is forbidden
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'You cannot add your own item to wishlist'
        ]);

        // Verify item is not in wishlist
        $this->assertDatabaseMissing('wishlist', [
            'UserID' => $owner->UserID,
            'ItemID' => $item->ItemID,
        ]);
    }

    /**
     * Test that users can add other users' items to wishlist
     */
    public function test_user_can_add_others_items_to_wishlist()
    {
        // Create two users
        $owner = User::factory()->create();
        $renter = User::factory()->create();

        // Create necessary dependencies
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        // Create an item owned by first user
        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Act as the renter and add the owner's item to wishlist
        $response = $this->actingAs($renter)->postJson("/wishlist/toggle/{$item->ItemID}");

        // Assert that the request is successful
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'added' => true,
        ]);

        // Verify item is in wishlist
        $this->assertDatabaseHas('wishlist', [
            'UserID' => $renter->UserID,
            'ItemID' => $item->ItemID,
        ]);
    }

    /**
     * Test that item owner cannot use the add method either
     */
    public function test_item_owner_cannot_use_add_method()
    {
        // Create a user (item owner)
        $owner = User::factory()->create();

        // Create necessary dependencies
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        // Create an item owned by the user
        $item = Item::factory()->create([
            'UserID' => $owner->UserID,
            'CategoryID' => $category->CategoryID,
            'LocationID' => $location->LocationID,
        ]);

        // Act as the owner and try to add their own item to wishlist using add method
        $response = $this->actingAs($owner)->postJson("/wishlist/add/{$item->ItemID}");

        // Assert that the request is forbidden
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'You cannot add your own item to wishlist'
        ]);

        // Verify item is not in wishlist
        $this->assertDatabaseMissing('wishlist', [
            'UserID' => $owner->UserID,
            'ItemID' => $item->ItemID,
        ]);
    }
}
