<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Location;

class ItemSeeder extends Seeder
{
    public function run()
    {
        // Make sure you have at least one user
        $user = User::where('IsAdmin', 0)->first();
        
        if (!$user) {
            // Create a sample user if none exists
            $user = User::create([
                'UserName' => 'Sample User',
                'Email' => 'sample@example.com',
                'PasswordHash' => bcrypt('password'),
                'UserType' => 'Lender',
                'IsAdmin' => 0
            ]);
        }

        $items = [
            [
                'UserID' => $user->UserID,
                'ItemName' => 'PlayStation 5',
                'Description' => 'Latest PS5 console with 2 controllers. Perfect condition, includes popular games.',
                'CategoryID' => Category::where('CategoryName', 'Gaming')->first()->CategoryID,
                'LocationID' => Location::where('LocationName', 'Kuala Lumpur')->first()->LocationID,
                'Availability' => 1,
                'DepositAmount' => 1500.00,
                'PricePerDay' => 50.00,
                'DateAdded' => now(),
            ],
            [
                'UserID' => $user->UserID,
                'ItemName' => 'Canon EOS R6',
                'Description' => 'Professional mirrorless camera with 24-70mm lens. Great for events and photography.',
                'CategoryID' => Category::where('CategoryName', 'Photography')->first()->CategoryID,
                'LocationID' => Location::where('LocationName', 'Penang')->first()->LocationID,
                'Availability' => 1,
                'DepositAmount' => 3000.00,
                'PricePerDay' => 120.00,
                'DateAdded' => now(),
            ],
            [
                'UserID' => $user->UserID,
                'ItemName' => 'MacBook Pro M2',
                'Description' => '14-inch MacBook Pro with M2 chip, 16GB RAM, 512GB SSD. Perfect for work or study.',
                'CategoryID' => Category::where('CategoryName', 'Computer')->first()->CategoryID,
                'LocationID' => Location::where('LocationName', 'Johor Bahru')->first()->LocationID,
                'Availability' => 1,
                'DepositAmount' => 5000.00,
                'PricePerDay' => 80.00,
                'DateAdded' => now(),
            ],
            [
                'UserID' => $user->UserID,
                'ItemName' => 'Fender Stratocaster',
                'Description' => 'Electric guitar with amplifier. Perfect for practice or small gigs.',
                'CategoryID' => Category::where('CategoryName', 'Music')->first()->CategoryID,
                'LocationID' => Location::where('LocationName', 'Selangor')->first()->LocationID,
                'Availability' => 1,
                'DepositAmount' => 800.00,
                'PricePerDay' => 35.00,
                'DateAdded' => now(),
            ],
            [
                'UserID' => $user->UserID,
                'ItemName' => 'Wedding Suit',
                'Description' => 'Premium designer wedding suit, black tuxedo. Dry cleaned and ready to wear.',
                'CategoryID' => Category::where('CategoryName', 'Attire')->first()->CategoryID,
                'LocationID' => Location::where('LocationName', 'Kuala Lumpur')->first()->LocationID,
                'Availability' => 1,
                'DepositAmount' => 500.00,
                'PricePerDay' => 100.00,
                'DateAdded' => now(),
            ],
            [
                'UserID' => $user->UserID,
                'ItemName' => 'Mountain Bike',
                'Description' => 'Trek mountain bike, 21-speed. Perfect for trails and outdoor adventures.',
                'CategoryID' => Category::where('CategoryName', 'Sport')->first()->CategoryID,
                'LocationID' => Location::where('LocationName', 'Penang')->first()->LocationID,
                'Availability' => 1,
                'DepositAmount' => 600.00,
                'PricePerDay' => 40.00,
                'DateAdded' => now(),
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}