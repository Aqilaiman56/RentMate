<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'CategoryName' => fake()->randomElement([
                'Electronics',
                'Vehicles',
                'Tools',
                'Sports Equipment',
                'Party Supplies',
                'Photography',
                'Camping Gear',
                'Musical Instruments'
            ]),
        ];
    }
}
