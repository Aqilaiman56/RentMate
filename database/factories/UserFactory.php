<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'UserName' => fake()->name(),
            'Email' => fake()->unique()->safeEmail(),
            'PasswordHash' => static::$password ??= Hash::make('password'),
            'email_verified_at' => now(),
            'PhoneNumber' => fake()->phoneNumber(),
            'Location' => fake()->city(),
            'UserType' => fake()->randomElement(['Student', 'Faculty', 'Staff']),
            'IsAdmin' => 0,
            'IsSuspended' => 0,
            'role' => 'user',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'IsSuspended' => 1,
            'SuspendedUntil' => now()->addDays(30),
            'SuspensionReason' => 'Test suspension',
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'IsAdmin' => 1,
            'role' => 'admin',
        ]);
    }
}
