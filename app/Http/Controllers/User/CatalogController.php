<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function shop(Request $request): View
    {
        $products = $this->baseQuery($request)
            ->latest('created_at')
            ->paginate(16)
            ->withQueryString();

        return view('shop', compact('products'));
    }

    public function search(Request $request): View
    {
        $products = $this->baseQuery($request)
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('search', compact('products'));
    }

    private function baseQuery(Request $request)
    {
        return Product::query()
            ->with(['images', 'variants'])
            ->where('status', 'active')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->query('q'));

                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', '%' . $term . '%')
                        ->orWhere('description', 'like', '%' . $term . '%');
                });
            });
    }
}

