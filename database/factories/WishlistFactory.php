<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'UserID' => User::factory(),
            'ItemID' => Item::factory(),
            'DateAdded' => now(),
        ];
    }
}
