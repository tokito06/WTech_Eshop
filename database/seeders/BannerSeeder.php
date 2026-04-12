<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Image;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            ['name' => 'Summer Collection', 'description' => 'Discover the hottest looks of the season', 'image_path' => 'images/image_1.jpg', 'sort_order' => 0],
            ['name' => 'New Arrivals',       'description' => 'Fresh styles just dropped — shop now',     'image_path' => 'images/image_2.jpg', 'sort_order' => 1],
            ['name' => 'Sale Up To 50%',     'description' => 'Limited time deals on selected items',     'image_path' => 'images/image_3.jpg', 'sort_order' => 2],
        ];

        foreach ($banners as $data) {
            $image = Image::create([
                'name'     => $data['name'],
                'path'     => $data['image_path'],
                'position' => $data['sort_order'],
            ]);

            Banner::create([
                'name'        => $data['name'],
                'description' => $data['description'],
                'sort_order'  => $data['sort_order'],
                'image_id'    => $image->id,
            ]);
        }
    }
}
