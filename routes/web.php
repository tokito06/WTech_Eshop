<?php

use Illuminate\Support\Facades\Route;

// Home
Route::get('/', fn() => view('index'))->name('home');

// Shop
Route::get('/shop', fn() => view('shop'))->name('shop');
Route::get('/product', fn() => view('product'))->name('product');
Route::get('/search', fn() => view('search'))->name('search');

// User
Route::get('/cart', fn() => view('cart'))->name('cart');
Route::get('/favourites', fn() => view('favourites'))->name('favourites');
Route::get('/profile', fn() => view('profile'))->name('profile');

// Checkout
Route::get('/delivery', fn() => view('delivery'))->name('delivery');
Route::get('/payment', fn() => view('payment'))->name('payment');
Route::get('/order-success', fn() => view('order-success'))->name('order.success');

// Auth
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/products', fn() => view('admin.products'))->name('products');
    Route::get('/products/create', fn() => view('admin.add-product'))->name('products.create');
    Route::get('/products/edit', fn() => view('admin.edit-product'))->name('products.edit');
});
