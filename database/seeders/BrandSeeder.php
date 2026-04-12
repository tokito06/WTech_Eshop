<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::where('email', 'admin@example.com')->first();

        // Seller's own brands
        $sellerBrands = ['Zara', 'Nike'];
        foreach ($sellerBrands as $name) {
            Brand::create(['name' => $name, 'user_id' => $seller?->id]);
        }

        // Unowned brands (available in the system but no seller assigned)
        $globalBrands = ['Adidas', 'Puma', 'New Balance', 'H&M', 'Uniqlo'];
        foreach ($globalBrands as $name) {
            Brand::create(['name' => $name]);
        }
    }
}
