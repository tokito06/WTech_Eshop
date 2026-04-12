<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Nike', 'Adidas', 'Puma', 'New Balance', 'Zara', 'H&M', 'Uniqlo'];

        foreach ($brands as $name) {
            Brand::create(['name' => $name]);
        }
    }
}
