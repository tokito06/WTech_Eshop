<?php

use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('home');
Route::view('/shop', 'shop')->name('shop');
Route::view('/search', 'search')->name('search');
Route::view('/product', 'product')->name('product');

Route::middleware('auth')->group(function () {
    Route::view('/cart', 'cart')->name('cart');
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

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::view('/products', 'admin.products')->name('products');
        Route::view('/products/create', 'admin.add-product')->name('products.create');
        Route::view('/products/edit', 'admin.edit-product')->name('products.edit');
    });
});

require __DIR__.'/auth.php';