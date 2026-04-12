<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(?Product $product = null): View
    {
        if (!$product) {
            $product = Product::query()
                ->where('status', 'active')
                ->with(['variants', 'images', 'category'])
                ->latest('created_at')
                ->first();
        } else {
            $product->loadMissing(['variants', 'images', 'category']);
        }

        $variantId = $product?->variants?->first()?->id;

        return view('product', compact('product', 'variantId'));
    }
}

