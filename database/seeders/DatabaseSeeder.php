<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            BannerSeeder::class,
            DeliveryMethodSeeder::class,
            ProductSeeder::class,
        ]);

        User::factory()->create([
            'name'      => 'Test',
            'surname'   => 'User',
            'email'     => 'test@example.com',
            'user_type' => 'buyer',
        ]);

        User::factory()->create([
            'name'      => 'Admin',
            'surname'   => 'Seller',
            'email'     => 'admin@example.com',
            'user_type' => 'seller',
        ]);
    }
}
