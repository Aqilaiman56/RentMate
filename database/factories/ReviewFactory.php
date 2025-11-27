<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'UserID' => User::factory(),
            'ItemID' => Item::factory(),
            'Rating' => fake()->numberBetween(1, 5),
            'Comment' => fake()->paragraph(),
            'ReviewImage' => null,
            'DatePosted' => now(),
            'IsReported' => false,
        ];
    }

    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'ReviewImage' => 'review_images/' . fake()->uuid() . '.jpg',
        ]);
    }
}
