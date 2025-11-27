<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 week');
        $endDate = fake()->dateTimeBetween($startDate, '+2 weeks');
        $days = max(1, $startDate->diff($endDate)->days);
        $pricePerDay = fake()->randomFloat(2, 10, 200);
        $depositAmount = fake()->randomFloat(2, 50, 500);
        $serviceFee = ($pricePerDay * $days) * 0.10; // 10% service fee
        $totalAmount = ($pricePerDay * $days) + $depositAmount;

        return [
            'UserID' => User::factory(),
            'ItemID' => Item::factory(),
            'StartDate' => $startDate,
            'EndDate' => $endDate,
            'TotalAmount' => $totalAmount,
            'DepositAmount' => $depositAmount,
            'ServiceFeeAmount' => $serviceFee,
            'TotalPaid' => $totalAmount + $serviceFee,
            'Status' => 'Pending',
            'ReturnConfirmed' => false,
            'BookingDate' => now(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'Approved',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'Completed',
            'ReturnConfirmed' => true,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'Rejected',
        ]);
    }
}
