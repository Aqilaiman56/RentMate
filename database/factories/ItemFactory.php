<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);

        return [
            'UserID' => User::factory(),
            'ItemName' => fake()->words(3, true),
            'Description' => fake()->paragraph(),
            'CategoryID' => Category::factory(),
            'LocationID' => Location::factory(),
            'DepositAmount' => fake()->randomFloat(2, 50, 500),
            'PricePerDay' => fake()->randomFloat(2, 10, 200),
            'Availability' => true,
            'Quantity' => $quantity,
            'AvailableQuantity' => $quantity,
            'DateAdded' => now(),
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'Availability' => false,
            'AvailableQuantity' => 0,
        ]);
    }
}
