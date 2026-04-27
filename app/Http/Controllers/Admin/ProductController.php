<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

    public function index(Request $request): View
    {
        $query = Product::with(['brand', 'category', 'images', 'variants']);

        if (!auth()->user()->isSuperAdmin()) {
            $query->whereIn('brand_id', $this->sellerBrandIds());
        }

        if ($request->status && in_array($request->status, ['active', 'archived'])) {
            $query->where('status', $request->status);
        }

        if (auth()->user()->isSuperAdmin() && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->latest('created_at')->paginate(20)->withQueryString();
        $allBrands = auth()->user()->isSuperAdmin() ? Brand::orderBy('name')->get() : collect();

        return view('admin.products', compact('products', 'allBrands'));
    }

    public function create(): View
    {
        $brands     = $this->accessibleBrands();
        $categories = Category::orderBy('name')->get();

        return view('admin.add-product', compact('brands', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'brand_id'    => ['required', 'integer', 'in:' . implode(',', $this->accessibleBrandIds())],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sex'         => ['required', 'in:men,women,kids,unisex'],
            'price'       => ['required', 'numeric', 'min:0'],
            'images'      => ['nullable', 'array'],
            'images.*'    => ['image', 'max:4096'],
            'inventory'   => ['nullable', 'array'],
            'inventory.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $product = Product::create([
            'name'        => $data['name'],
            'description' => $data['description'],
            'brand_id'    => $data['brand_id'],
            'category_id' => $data['category_id'],
            'sex'         => $data['sex'],
            'status'      => 'active',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                if (!$file) {
                    continue;
                }

                $path  = $file->store('products', 'public');
                $image = Image::create([
                    'name'     => $product->name,
                    'path'     => $path,
                    'position' => $index,
                ]);
                $product->images()->attach($image->id);
            }
        }

        foreach (self::SIZES as $symbol) {
            $inventory = (int) ($data['inventory'][$symbol] ?? 0);
            ProductVariant::create([
                'product_id' => $product->id,
                'symbol'     => $symbol,
                'price'      => $data['price'],
                'inventory'  => $inventory,
            ]);
        }

        return redirect()->route('admin.products')->with('success', 'Product added successfully.');
    }

    public function edit(Product $product): View
    {
        $this->authorizeProduct($product);

        $product->load(['images', 'variants', 'brand', 'category']);
        $brands         = $this->accessibleBrands();
        $categories     = Category::orderBy('name')->get();
        $variantsBySize = $product->variants->keyBy('symbol');

        return view('admin.edit-product', compact('product', 'brands', 'categories', 'variantsBySize'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'brand_id'    => ['required', 'integer', 'in:' . implode(',', $this->accessibleBrandIds())],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sex'         => ['required', 'in:men,women,kids,unisex'],
            'status'      => ['required', 'in:active,archived'],
            'price'       => ['required', 'numeric', 'min:0'],
            'image'       => ['nullable', 'image', 'max:4096'],
            'inventory'   => ['nullable', 'array'],
            'inventory.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $product->update([
            'name'        => $data['name'],
            'description' => $data['description'],
            'brand_id'    => $data['brand_id'],
            'category_id' => $data['category_id'],
            'sex'         => $data['sex'],
            'status'      => $data['status'],
        ]);

        if ($request->hasFile('image')) {
            $path  = $request->file('image')->store('products', 'public');
            $image = Image::create([
                'name'     => $product->name,
                'path'     => $path,
                'position' => 0,
            ]);
            $product->images()->syncWithoutDetaching([$image->id]);
        }

        foreach (self::SIZES as $symbol) {
            $inventory = (int) ($data['inventory'][$symbol] ?? 0);
            ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'symbol' => $symbol],
                ['price' => $data['price'], 'inventory' => $inventory]
            );
        }

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted.');
    }

    // --- Helpers ---

    private function sellerBrandIds(): array
    {
        return auth()->user()->brands->pluck('id')->toArray();
    }

    private function accessibleBrandIds(): array
    {
        if (auth()->user()->isSuperAdmin()) {
            return Brand::pluck('id')->toArray();
        }
        return $this->sellerBrandIds();
    }

    private function accessibleBrands()
    {
        if (auth()->user()->isSuperAdmin()) {
            return Brand::orderBy('name')->get();
        }
        return auth()->user()->brands;
    }

    private function authorizeProduct(Product $product): void
    {
        if (auth()->user()->isSuperAdmin()) {
            return;
        }
        abort_unless(in_array($product->brand_id, $this->sellerBrandIds()), 403);
    }
}
