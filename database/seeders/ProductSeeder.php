<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $outerwear  = Category::where('name', 'Outerwear')->first();
        $accessories = Category::where('name', 'Accessories')->first();
        $shirts     = Category::where('name', 'T-Shirts')->first();

        $zara   = Brand::where('name', 'Zara')->first();
        $nike   = Brand::where('name', 'Nike')->first();
        $hm     = Brand::where('name', 'H&M')->first();

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        $products = [
            [
                'name'        => 'Red Jacket',
                'description' => 'Water-resistant material with a slim cut, ideal for outdoor activities. Features ribbed cuffs and a zip-up front. Lightweight and packable design.',
                'brand_id'    => $zara->id,
                'category_id' => $outerwear->id,
                'sex'         => 'unisex',
                'price'       => 104.50,
                'image_name'  => 'Red Jacket',
                'image_path'  => 'images/image_1.jpg',
            ],
            [
                'name'        => 'Super View Glasses',
                'description' => 'UV-protective lenses with a lightweight frame. Wide field of view and polarised coating. Suitable for all face shapes.',
                'brand_id'    => $hm->id,
                'category_id' => $accessories->id,
                'sex'         => 'unisex',
                'price'       => 19.99,
                'image_name'  => 'Glasses',
                'image_path'  => 'images/image_2.jpg',
            ],
            [
                'name'        => 'White T-shirt',
                'description' => '100% cotton, relaxed fit crew-neck T-shirt. Premium cotton fabric for all-day comfort. Available in multiple colours.',
                'brand_id'    => $nike->id,
                'category_id' => $shirts->id,
                'sex'         => 'unisex',
                'price'       => 39.00,
                'image_name'  => 'White T-shirt',
                'image_path'  => 'images/image_3.jpg',
            ],
            [
                'name'        => 'Blue Sneakers',
                'description' => 'Breathable mesh upper with cushioned sole. Ideal for running and everyday wear. Non-slip rubber outsole.',
                'brand_id'    => $nike->id,
                'category_id' => Category::where('name', 'Sneakers')->first()->id,
                'sex'         => 'men',
                'price'       => 89.00,
                'image_name'  => 'Blue Sneakers',
                'image_path'  => 'images/image_1.jpg',
            ],
            [
                'name'        => 'Leather Bag',
                'description' => 'Genuine leather tote bag with inner zip pocket. Sturdy handles and magnetic closure. Spacious main compartment.',
                'brand_id'    => $zara->id,
                'category_id' => Category::where('name', 'Bags')->first()->id,
                'sex'         => 'women',
                'price'       => 59.00,
                'image_name'  => 'Leather Bag',
                'image_path'  => 'images/image_2.jpg',
            ],
            [
                'name'        => 'Sports Shorts',
                'description' => 'Lightweight moisture-wicking fabric. Elastic waistband with drawstring. Side pockets for convenience.',
                'brand_id'    => $nike->id,
                'category_id' => Category::where('name', 'Sportswear')->first()->id,
                'sex'         => 'men',
                'price'       => 29.99,
                'image_name'  => 'Sports Shorts',
                'image_path'  => 'images/image_3.jpg',
            ],
        ];

        foreach ($products as $data) {
            $image = Image::create([
                'name'     => $data['image_name'],
                'path'     => $data['image_path'],
                'position' => 0,
            ]);

            $product = Product::create([
                'name'        => $data['name'],
                'description' => $data['description'],
                'brand_id'    => $data['brand_id'],
                'category_id' => $data['category_id'],
                'sex'         => $data['sex'],
                'status'      => 'active',
            ]);

            $product->images()->attach($image->id);

            foreach ($sizes as $symbol) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'symbol'     => $symbol,
                    'price'      => $data['price'],
                    'inventory'  => rand(0, 20),
                ]);
            }
        }
    }
}
