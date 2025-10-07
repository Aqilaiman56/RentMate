<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['CategoryName' => 'Gaming'],
            ['CategoryName' => 'Music'],
            ['CategoryName' => 'Computer'],
            ['CategoryName' => 'Photography'],
            ['CategoryName' => 'Attire'],
            ['CategoryName' => 'Books'],
            ['CategoryName' => 'Events'],
            ['CategoryName' => 'Sport'],
            ['CategoryName' => 'Electric'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}