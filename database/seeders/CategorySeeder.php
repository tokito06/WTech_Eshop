<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Outerwear',  'icon' => 'apparel'],
            ['name' => 'Accessories','icon' => 'diamond'],
            ['name' => 'T-Shirts',   'icon' => 'apparel'],
            ['name' => 'Sneakers',   'icon' => 'steps'],
            ['name' => 'Bags',       'icon' => 'shopping_bag'],
            ['name' => 'Sportswear', 'icon' => 'sports'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
