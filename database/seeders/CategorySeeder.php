<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Shoes',      'icon' => 'steps'],
            ['name' => 'Shirts',     'icon' => 'apparel'],
            ['name' => 'Trousers',   'icon' => 'apparel'],
            ['name' => 'Accessories','icon' => 'diamond'],
            ['name' => 'Bags',       'icon' => 'shopping_bag'],
            ['name' => 'Jewelry',    'icon' => 'diamond'],
            ['name' => 'Outerwear',  'icon' => 'apparel'],
            ['name' => 'Sportswear', 'icon' => 'sports'],
            ['name' => 'Sneakers',   'icon' => 'steps'],
            ['name' => 'T-Shirts',   'icon' => 'apparel'],
            ['name' => 'Jeans',      'icon' => 'apparel'],
            ['name' => 'Watches',    'icon' => 'watch'],
            ['name' => 'Backpacks',  'icon' => 'backpack'],
            ['name' => 'Rings',      'icon' => 'diamond'],
            ['name' => 'Coats',      'icon' => 'apparel'],
            ['name' => 'Training',   'icon' => 'sports_gymnastics'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
