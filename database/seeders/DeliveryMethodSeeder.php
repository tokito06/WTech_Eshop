<?php

namespace Database\Seeders;

use App\Models\DeliveryMethod;
use Illuminate\Database\Seeder;
// Str import removed — HasUuids handles UUID generation
class DeliveryMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Slovenská pošta', 'brief' => 'Post office pickup',    'expected_time' => 5, 'price' => 0.00],
            ['name' => 'Packeta',          'brief' => 'Pickup/home delivery', 'expected_time' => 3, 'price' => 1.99],
            ['name' => 'GLS Slovakia',     'brief' => 'Courier to door',      'expected_time' => 2, 'price' => 2.99],
            ['name' => 'DHL Express',      'brief' => 'Express courier',      'expected_time' => 1, 'price' => 9.99],
        ];

        foreach ($methods as $method) {
            DeliveryMethod::create($method);
        }
    }
}
