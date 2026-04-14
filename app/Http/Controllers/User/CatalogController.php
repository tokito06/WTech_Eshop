<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterProductsRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function shop(Request $request): View
    {
        $allowedSex = ['men', 'women', 'kids'];
        $activeSex = in_array($request->query('sex'), $allowedSex, true) ? $request->query('sex') : null;

        $filters = [];
        if ($activeSex !== null) {
            $filters['sex'] = $activeSex;
        }

        $products = $this->baseQuery($request, $filters)
            ->paginate(20)
            ->withQueryString();

        return view('shop', compact('products', 'activeSex'));
    }

    public function search(FilterProductsRequest $request): View
    {
        $filters = $request->validated();

        $products = $this->baseQuery($request, $filters)
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->whereHas('products', fn (Builder $query) => $query->where('status', 'active'))
            ->orderBy('name')
            ->get(['id', 'name']);

        $brands = Brand::query()
            ->whereHas('products', fn (Builder $query) => $query->where('status', 'active'))
            ->orderBy('name')
            ->get(['id', 'name']);

        $sizes = ProductVariant::query()
            ->whereHas('product', fn (Builder $query) => $query->where('status', 'active'))
            ->select('symbol')
            ->distinct()
            ->orderBy('symbol')
            ->pluck('symbol');

        return view('search', compact('products', 'categories', 'brands', 'sizes', 'filters'));
    }

    private function baseQuery(Request $request, array $filters = []): Builder
    {
        $query = Product::query()
            ->with(['images', 'variants'])
            ->where('status', 'active')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->query('q'));

                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', '%' . $term . '%')
                        ->orWhere('description', 'like', '%' . $term . '%');
                });
            })
            ->when(!empty($filters['category_id']), fn (Builder $query) => $query->where('category_id', $filters['category_id']))
            ->when(!empty($filters['brand_id']), fn (Builder $query) => $query->where('brand_id', $filters['brand_id']))
            ->when(!empty($filters['sex']), fn (Builder $query) => $query->where('sex', $filters['sex']))
            ->when(
                !empty($filters['sizes']),
                fn (Builder $query) => $query->whereHas('variants', fn (Builder $variantQuery) => $variantQuery->whereIn(DB::raw('UPPER(TRIM(symbol))'), $filters['sizes']))
            )
            ->when(
                (array_key_exists('price_min', $filters) && $filters['price_min'] !== null)
                || (array_key_exists('price_max', $filters) && $filters['price_max'] !== null),
                function (Builder $query) use ($filters) {
                    $query->whereHas('variants', function (Builder $variantQuery) use ($filters) {
                        if (array_key_exists('price_min', $filters) && $filters['price_min'] !== null) {
                            $variantQuery->where('price', '>=', $filters['price_min']);
                        }

                        if (array_key_exists('price_max', $filters) && $filters['price_max'] !== null) {
                            $variantQuery->where('price', '<=', $filters['price_max']);
                        }
                    });
                }
            );

        $sort = $filters['sort'] ?? null;

        if ($sort === 'price_asc') {
            $query->withMin('variants', 'price')
                ->orderBy('variants_min_price')
                ->latest('created_at');

            return $query;
        }

        if ($sort === 'price_desc') {
            $query->withMin('variants', 'price')
                ->orderByDesc('variants_min_price')
                ->latest('created_at');

            return $query;
        }

        return $query->latest('created_at');
    }
}

