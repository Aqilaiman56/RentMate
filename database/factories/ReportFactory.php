<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ReportedByID' => User::factory(),
            'ReportedUserID' => User::factory(),
            'BookingID' => Booking::factory(),
            'ItemID' => Item::factory(),
            'ReportType' => fake()->randomElement(['Damage', 'Misconduct', 'Fraud', 'Late Return', 'Other']),
            'Priority' => fake()->randomElement(['Low', 'Medium', 'High', 'Critical']),
            'Subject' => fake()->sentence(),
            'Description' => fake()->paragraph(),
            'EvidencePath' => null,
            'Status' => 'pending',
            'ReviewedByAdminID' => null,
            'AdminNotes' => null,
            'DateReported' => now(),
            'DateResolved' => null,
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'resolved',
            'ReviewedByAdminID' => User::factory()->create(['IsAdmin' => true])->UserID,
            'DateResolved' => now(),
            'AdminNotes' => 'Report has been resolved',
        ]);
    }

    public function dismissed(): static
    {
        return $this->state(fn (array $attributes) => [
            'Status' => 'dismissed',
            'ReviewedByAdminID' => User::factory()->create(['IsAdmin' => true])->UserID,
            'DateResolved' => now(),
            'AdminNotes' => 'Report dismissed after review',
        ]);
    }
}
