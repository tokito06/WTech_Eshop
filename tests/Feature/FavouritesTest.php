<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createProductForFavouritesTest(): Product
{
    $seller = User::factory()->create([
        'user_type' => 'seller',
    ]);

    $brand = Brand::create([
        'name' => 'Fav Brand ' . fake()->unique()->word(),
        'user_id' => $seller->id,
    ]);

    $category = Category::create([
        'name' => 'Fav Category ' . fake()->unique()->word(),
    ]);

    return Product::create([
        'name' => 'Fav Product',
        'description' => 'Fav product description',
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'sex' => 'unisex',
        'status' => 'active',
    ]);
}

it('rejects guests for favourites api endpoints', function () {
    $product = createProductForFavouritesTest();

    $this->postJson(route('favourites.toggle'), [
        'product_id' => $product->id,
    ])->assertStatus(401);

    $this->deleteJson(route('favourites.remove', $product))
        ->assertStatus(401);
});

it('toggles favourites on and off', function () {
    $user = User::factory()->create([
        'user_type' => 'buyer',
    ]);
    $product = createProductForFavouritesTest();

    $this->actingAs($user)->postJson(route('favourites.toggle'), [
        'product_id' => $product->id,
    ])->assertOk()->assertJson([
        'success' => true,
        'favourited' => true,
    ]);

    $this->assertDatabaseHas('favourites', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($user)->postJson(route('favourites.toggle'), [
        'product_id' => $product->id,
    ])->assertOk()->assertJson([
        'success' => true,
        'favourited' => false,
    ]);

    $this->assertDatabaseMissing('favourites', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

it('removes favourites via delete endpoint', function () {
    $user = User::factory()->create([
        'user_type' => 'buyer',
    ]);
    $product = createProductForFavouritesTest();

    $user->favourites()->attach($product->id, ['added_at' => now()]);

    $this->assertDatabaseHas('favourites', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($user)->deleteJson(route('favourites.remove', $product))
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('favourites', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

