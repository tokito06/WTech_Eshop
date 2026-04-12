<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with(['images', 'variants'])
            ->where('status', 'active')
            ->latest('created_at')
            ->take(12)
            ->get();

        return view('index', compact('products'));
    }
}

