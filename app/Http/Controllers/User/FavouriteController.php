<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavouriteController extends Controller
{
    public function index(Request $request): View
    {
        $favourites = $request->user()
            ->favourites()
            ->with(['images', 'variants'])
            ->orderByPivot('added_at', 'desc')
            ->get();

        return view('favourites', compact('favourites'));
    }

    public function toggle(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'uuid', 'exists:products,id'],
        ]);

        $user = $request->user();
        $productId = $data['product_id'];

        $alreadyFavourited = $user->favourites()
            ->where('product_id', $productId)
            ->exists();

        if ($alreadyFavourited) {
            $user->favourites()->detach($productId);
        } else {
            $user->favourites()->attach($productId, ['added_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'favourited' => !$alreadyFavourited,
        ]);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $request->user()->favourites()->detach($product->id);

        return response()->json([
            'success' => true,
        ]);
    }
}

