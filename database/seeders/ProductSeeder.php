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
        $outerwear   = Category::where('name', 'Outerwear')->first();
        $accessories = Category::where('name', 'Accessories')->first();
        $shirts      = Category::where('name', 'T-Shirts')->first();
        $sneakers    = Category::where('name', 'Sneakers')->first();
        $bags        = Category::where('name', 'Bags')->first();
        $sportswear  = Category::where('name', 'Sportswear')->first();

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
                'category_id' => $bags->id,
                'sex'         => 'women',
                'price'       => 59.00,
                'image_name'  => 'Leather Bag',
                'image_path'  => 'images/image_2.jpg',
            ],
            [
                'name'        => 'Sports Shorts',
                'description' => 'Lightweight moisture-wicking fabric. Elastic waistband with drawstring. Side pockets for convenience.',
                'brand_id'    => $nike->id,
                'category_id' => $sportswear->id,
                'sex'         => 'men',
                'price'       => 29.99,
                'image_name'  => 'Sports Shorts',
                'image_path'  => 'images/image_3.jpg',
            ],
            [
                'name'        => 'Zara Parka',
                'description' => 'Warm insulated parka with adjustable hood and storm flap. Designed for cold weather layering.',
                'brand_id'    => $zara->id,
                'category_id' => $outerwear->id,
                'sex'         => 'women',
                'price'       => 129.00,
                'image_name'  => 'Zara Parka',
                'image_path'  => 'images/image_1.jpg',
            ],
            [
                'name'        => 'Zara Windbreaker',
                'description' => 'Lightweight windbreaker with mesh lining and zip pockets. Packs down easily for travel.',
                'brand_id'    => $zara->id,
                'category_id' => $outerwear->id,
                'sex'         => 'men',
                'price'       => 79.00,
                'image_name'  => 'Zara Windbreaker',
                'image_path'  => 'images/image_2.jpg',
            ],
            [
                'name'        => 'HM Utility Jacket',
                'description' => 'Cotton utility jacket with snap buttons and chest pockets. Everyday mid-layer style.',
                'brand_id'    => $hm->id,
                'category_id' => $outerwear->id,
                'sex'         => 'unisex',
                'price'       => 69.00,
                'image_name'  => 'Utility Jacket',
                'image_path'  => 'images/image_3.jpg',
            ],
            [
                'name'        => 'Nike Training Tee',
                'description' => 'Sweat-wicking fabric with flat seams for comfort. Athletic fit with a soft hand feel.',
                'brand_id'    => $nike->id,
                'category_id' => $shirts->id,
                'sex'         => 'men',
                'price'       => 34.00,
                'image_name'  => 'Training Tee',
                'image_path'  => 'images/image_1.jpg',
            ],
            [
                'name'        => 'Zara Graphic Tee',
                'description' => 'Relaxed fit graphic tee with breathable cotton jersey and ribbed neckline.',
                'brand_id'    => $zara->id,
                'category_id' => $shirts->id,
                'sex'         => 'women',
                'price'       => 29.00,
                'image_name'  => 'Graphic Tee',
                'image_path'  => 'images/image_2.jpg',
            ],
            [
                'name'        => 'HM Soft Tee',
                'description' => 'Soft-touch cotton tee with a straight hem and easy everyday fit.',
                'brand_id'    => $hm->id,
                'category_id' => $shirts->id,
                'sex'         => 'unisex',
                'price'       => 19.00,
                'image_name'  => 'Soft Tee',
                'image_path'  => 'images/image_3.jpg',
            ],
            [
                'name'        => 'Nike Runner Pro',
                'description' => 'Responsive foam midsole with breathable upper and durable outsole for daily runs.',
                'brand_id'    => $nike->id,
                'category_id' => $sneakers->id,
                'sex'         => 'men',
                'price'       => 110.00,
                'image_name'  => 'Runner Pro',
                'image_path'  => 'images/image_2.jpg',
            ],
            [
                'name'        => 'Zara Street Sneaker',
                'description' => 'Low-profile sneaker with padded collar and textured rubber sole.',
                'brand_id'    => $zara->id,
                'category_id' => $sneakers->id,
                'sex'         => 'women',
                'price'       => 74.00,
                'image_name'  => 'Street Sneaker',
                'image_path'  => 'images/image_3.jpg',
            ],
            [
                'name'        => 'HM Canvas Sneaker',
                'description' => 'Classic canvas sneaker with cushioned insole and flexible outsole.',
                'brand_id'    => $hm->id,
                'category_id' => $sneakers->id,
                'sex'         => 'unisex',
                'price'       => 39.00,
                'image_name'  => 'Canvas Sneaker',
                'image_path'  => 'images/image_1.jpg',
            ],
            [
                'name'        => 'Zara City Tote',
                'description' => 'Structured tote with metal hardware and inner organizer pockets.',
                'brand_id'    => $zara->id,
                'category_id' => $bags->id,
                'sex'         => 'women',
                'price'       => 84.00,
                'image_name'  => 'City Tote',
                'image_path'  => 'images/image_2.jpg',
            ],
            [
                'name'        => 'Nike Gym Duffel',
                'description' => 'Durable duffel with ventilated shoe pocket and adjustable shoulder strap.',
                'brand_id'    => $nike->id,
                'category_id' => $bags->id,
                'sex'         => 'men',
                'price'       => 65.00,
                'image_name'  => 'Gym Duffel',
                'image_path'  => 'images/image_3.jpg',
            ],
            [
                'name'        => 'HM Crossbody Bag',
                'description' => 'Compact crossbody with adjustable strap and front zip pocket.',
                'brand_id'    => $hm->id,
                'category_id' => $bags->id,
                'sex'         => 'women',
                'price'       => 32.00,
                'image_name'  => 'Crossbody Bag',
                'image_path'  => 'images/image_1.jpg',
            ],
            [
                'name'        => 'Nike Running Shorts',
                'description' => 'Breathable shorts with reflective details and secure back pocket.',
                'brand_id'    => $nike->id,
                'category_id' => $sportswear->id,
                'sex'         => 'men',
                'price'       => 35.00,
                'image_name'  => 'Running Shorts',
                'image_path'  => 'images/image_2.jpg',
            ],
            [
                'name'        => 'Zara Sport Top',
                'description' => 'Stretchy sport top with quick-dry finish and supportive fit.',
                'brand_id'    => $zara->id,
                'category_id' => $sportswear->id,
                'sex'         => 'women',
                'price'       => 42.00,
                'image_name'  => 'Sport Top',
                'image_path'  => 'images/image_3.jpg',
            ],
            [
                'name'        => 'HM Track Pants',
                'description' => 'Comfort fit track pants with tapered leg and side stripe.',
                'brand_id'    => $hm->id,
                'category_id' => $sportswear->id,
                'sex'         => 'unisex',
                'price'       => 38.00,
                'image_name'  => 'Track Pants',
                'image_path'  => 'images/image_1.jpg',
            ],
            [
                'name'        => 'Zara Aviator Sunglasses',
                'description' => 'Classic aviator frame with UV400 lenses and adjustable nose pads.',
                'brand_id'    => $zara->id,
                'category_id' => $accessories->id,
                'sex'         => 'unisex',
                'price'       => 24.00,
                'image_name'  => 'Aviator Sunglasses',
                'image_path'  => 'images/image_2.jpg',
            ],
            [
                'name'        => 'Nike Sport Cap',
                'description' => 'Moisture-wicking cap with adjustable strap and embroidered logo.',
                'brand_id'    => $nike->id,
                'category_id' => $accessories->id,
                'sex'         => 'unisex',
                'price'       => 22.00,
                'image_name'  => 'Sport Cap',
                'image_path'  => 'images/image_3.jpg',
            ],
            [
                'name'        => 'HM Minimal Belt',
                'description' => 'Slim belt with matte buckle and adjustable sizing.',
                'brand_id'    => $hm->id,
                'category_id' => $accessories->id,
                'sex'         => 'unisex',
                'price'       => 18.00,
                'image_name'  => 'Minimal Belt',
                'image_path'  => 'images/image_1.jpg',
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
