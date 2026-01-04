<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['LocationName' => 'Inside UMS'],
            ['LocationName' => 'Outside UMS'],
            ['LocationName' => 'Kuala Lumpur'],
            ['LocationName' => 'Selangor'],
            ['LocationName' => 'Penang'],
            ['LocationName' => 'Johor Bahru'],
            ['LocationName' => 'Melaka'],
            ['LocationName' => 'Ipoh'],
            ['LocationName' => 'Kuching'],
            ['LocationName' => 'Kota Kinabalu'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}