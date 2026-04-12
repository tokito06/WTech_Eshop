<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CatalogController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProductController as UserProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [CatalogController::class, 'shop'])->name('shop');
Route::get('/search', [CatalogController::class, 'search'])->name('search');
Route::get('/product/{product?}', [UserProductController::class, 'show'])->name('product');

Route::middleware('auth')->group(function () {
    Route::view('/cart', 'cart')->name('cart');
    Route::get('/api/cart', [CartController::class, 'index'])->name('cart.get');
    Route::post('/api/cart/add', [CartController::class, 'store'])->name('cart.add');
    Route::put('/api/cart/item/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/api/cart/item/{item}', [CartController::class, 'destroy'])->name('cart.remove');

    Route::view('/favourites', 'favourites')->name('favourites');
    Route::view('/delivery', 'delivery')->name('delivery');
    Route::post('/delivery', [DeliveryController::class, 'store'])->name('delivery.store');
    Route::view('/payment', 'payment')->name('payment');
    Route::view('/order-success', 'order-success')->name('order.success');
});

Route::middleware('auth')->group(function () {
    Route::view('/profile', 'profile')->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('seller')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/brands', [BrandController::class, 'index'])->name('brands');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

        Route::get('/products', [AdminProductController::class, 'index'])->name('products');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    });
});

require __DIR__.'/auth.php';