<?php

use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createVariantForCartTest(): ProductVariant
{
    $seller = User::factory()->create([
        'user_type' => 'seller',
    ]);

    $brand = Brand::create([
        'name' => 'Test Brand ' . fake()->unique()->word(),
        'user_id' => $seller->id,
    ]);

    $category = Category::create([
        'name' => 'Test Category ' . fake()->unique()->word(),
    ]);

    $product = Product::create([
        'name' => 'Test Product',
        'description' => 'Test product description',
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'sex' => 'unisex',
        'status' => 'active',
    ]);

    return ProductVariant::create([
        'product_id' => $product->id,
        'symbol' => 'M',
        'price' => 99.99,
        'inventory' => 10,
    ]);
}

it('redirects guests to login when adding to cart', function () {
    $variant = createVariantForCartTest();

    $response = $this->postJson(route('cart.add'), [
        'variant_id' => $variant->id,
        'quantity' => 1,
    ]);

    $response->assertStatus(401);
});

it('adds item to authenticated user cart', function () {
    $user = User::factory()->create([
        'user_type' => 'buyer',
    ]);
    $variant = createVariantForCartTest();

    $response = $this->actingAs($user)->postJson(route('cart.add'), [
        'variant_id' => $variant->id,
        'quantity' => 2,
    ]);

    $response->assertOk()->assertJson(['success' => true]);

    $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
    $this->assertDatabaseCount('cart_items', 1);

    $item = CartItem::first();
    expect($item->quantity)->toBe(2);
});

it('merges duplicate variant quantities in cart', function () {
    $user = User::factory()->create([
        'user_type' => 'buyer',
    ]);
    $variant = createVariantForCartTest();

    $this->actingAs($user)->postJson(route('cart.add'), [
        'variant_id' => $variant->id,
        'quantity' => 1,
    ])->assertOk();

    $this->actingAs($user)->postJson(route('cart.add'), [
        'variant_id' => $variant->id,
        'quantity' => 3,
    ])->assertOk();

    $this->assertDatabaseCount('cart_items', 1);
    expect(CartItem::first()->quantity)->toBe(4);
});

