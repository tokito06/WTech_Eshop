<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::with('image')->orderBy('sort_order')->get();

        $categories = Category::all();

        $applyFavourites = function (Builder $query) use ($request): void {
            if (!$request->user()) {
                return;
            }

            $query->withExists([
                'favouritedBy as is_favourited' => fn (Builder $favQuery) => $favQuery
                    ->where('user_id', $request->user()->id),
            ]);
        };

        $menProducts = Product::with(['images', 'variants'])
            ->where('status', 'active')
            ->whereIn('sex', ['men', 'unisex'])
            ->tap($applyFavourites)
            ->latest('created_at')
            ->take(4)
            ->get();

        $womenProducts = Product::with(['images', 'variants'])
            ->where('status', 'active')
            ->whereIn('sex', ['women', 'unisex'])
            ->tap($applyFavourites)
            ->latest('created_at')
            ->take(4)
            ->get();

        $trendingProducts = Product::with(['images', 'variants'])
            ->where('status', 'active')
            ->tap($applyFavourites)
            ->latest('created_at')
            ->take(6)
            ->get();

        return view('index', compact('banners', 'categories', 'menProducts', 'womenProducts', 'trendingProducts'));
    }
}
