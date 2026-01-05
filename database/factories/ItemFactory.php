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
        return [
            'UserID' => User::factory(),
            'ItemName' => fake()->words(3, true),
            'Description' => fake()->paragraph(),
            'CategoryID' => Category::factory(),
            'LocationID' => Location::factory(),
            'DepositAmount' => fake()->randomFloat(2, 50, 500),
            'PricePerDay' => fake()->randomFloat(2, 10, 200),
            'Availability' => true,
            'Quantity' => fake()->numberBetween(1, 10),
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

    /**
     * Configure the model factory to sync AvailableQuantity with Quantity.
     */
    public function configure(): static
    {
        return $this->afterMaking(function ($item) {
            // Auto-set AvailableQuantity to match Quantity if not explicitly provided
            if (!array_key_exists('AvailableQuantity', $item->getAttributes())) {
                $item->AvailableQuantity = $item->Quantity;
            }
        });
    }
}
