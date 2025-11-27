<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'BookingID' => Booking::factory(),
            'BillCode' => fake()->unique()->numerify('BILL####'),
            'Amount' => fake()->randomFloat(2, 100, 1000),
            'PaymentMethod' => 'ToyyibPay',
            'Status' => 'pending',
            'TransactionID' => null,
            'PaymentResponse' => null,
            'PaymentDate' => null,
            'CreatedAt' => now(),
        ];
    }

    public function successful(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'successful',
            'TransactionID' => fake()->uuid(),
            'PaymentDate' => now(),
            'PaymentResponse' => json_encode(['status' => 'success']),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'failed',
            'PaymentResponse' => json_encode(['status' => 'failed']),
        ]);
    }
}
