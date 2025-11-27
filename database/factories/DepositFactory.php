<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
{
    public function definition(): array
    {
        return [
            'BookingID' => Booking::factory(),
            'DepositAmount' => fake()->randomFloat(2, 50, 500),
            'Status' => 'held',
            'DateCollected' => now(),
            'RefundDate' => null,
            'Notes' => null,
        ];
    }

    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'refunded',
            'RefundDate' => now(),
        ]);
    }

    public function forfeited(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'forfeited',
            'Notes' => 'Deposit forfeited due to violation',
        ]);
    }

    public function partial(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'partial',
            'RefundDate' => now(),
            'Notes' => 'Partial refund issued',
        ]);
    }
}
