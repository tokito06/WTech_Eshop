<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::with('image')->orderBy('sort_order')->get();

        $categories = Category::all();

        $menProducts = Product::with(['images', 'variants'])
            ->where('status', 'active')
            ->whereIn('sex', ['men', 'unisex'])
            ->latest('created_at')
            ->take(4)
            ->get();

        $womenProducts = Product::with(['images', 'variants'])
            ->where('status', 'active')
            ->whereIn('sex', ['women', 'unisex'])
            ->latest('created_at')
            ->take(4)
            ->get();

        $trendingProducts = Product::with(['images', 'variants'])
            ->where('status', 'active')
            ->latest('created_at')
            ->take(6)
            ->get();

        return view('index', compact('banners', 'categories', 'menProducts', 'womenProducts', 'trendingProducts'));
    }
}
