<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Request $request, ?Product $product = null): View
    {
        if (!$product) {
            $product = Product::query()
                ->where('status', 'active')
                ->with(['variants', 'images', 'category', 'brand'])
                ->when($request->user(), function ($query) use ($request) {
                    $query->withExists([
                        'favouritedBy as is_favourited' => fn ($favQuery) => $favQuery
                            ->where('user_id', $request->user()->id),
                    ]);
                })
                ->latest('created_at')
                ->first();
        } else {
            $product->loadMissing(['variants', 'images', 'category', 'brand']);

            if ($request->user()) {
                $product->setAttribute('is_favourited', $product->isFavouritedBy($request->user()));
            }
        }

        $variantId = $product?->variants?->first()?->id;

        return view('product', compact('product', 'variantId'));
    }
}
