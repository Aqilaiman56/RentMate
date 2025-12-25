<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenaltyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ReportID' => null,
            'ReportedByID' => User::factory(),
            'ReportedUserID' => User::factory(),
            'BookingID' => Booking::factory(),
            'ItemID' => Item::factory(),
            'ApprovedByAdminID' => User::factory()->create()->UserID,
            'Description' => fake()->paragraph(),
            'EvidencePath' => null,
            'PenaltyAmount' => fake()->randomFloat(2, 10, 500),
            'ResolvedStatus' => false,
            'DateReported' => now(),
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'ResolvedStatus' => true,
        ]);
    }
}
