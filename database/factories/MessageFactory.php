<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'SenderID' => User::factory(),
            'ReceiverID' => User::factory(),
            'ItemID' => Item::factory(),
            'MessageContent' => fake()->paragraph(),
            'IsRead' => false,
            'SentAt' => now(),
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'IsRead' => true,
        ]);
    }
}
