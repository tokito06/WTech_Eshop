<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $brandIds = auth()->user()->brands->pluck('id');

        $query = Product::with(['brand', 'category', 'images', 'variants'])
            ->whereIn('brand_id', $brandIds);

        if ($request->status && in_array($request->status, ['active', 'archived'])) {
            $query->where('status', $request->status);
        }

        $products = $query->latest('created_at')->paginate(20);

        return view('admin.products', compact('products'));
    }

    public function create(): View
    {
        $brands     = auth()->user()->brands;
        $categories = Category::orderBy('name')->get();

        return view('admin.add-product', compact('brands', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $sellerBrandIds = auth()->user()->brands->pluck('id')->toArray();

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'brand_id'    => ['required', 'integer', 'in:' . implode(',', $sellerBrandIds)],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sex'         => ['required', 'in:men,women,kids,unisex'],
            'price'       => ['required', 'numeric', 'min:0'],
            'image'       => ['nullable', 'image', 'max:4096'],
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

        if ($request->hasFile('image')) {
            $path  = $request->file('image')->store('products', 'public');
            $image = Image::create([
                'name'     => $product->name,
                'path'     => $path,
                'position' => 0,
            ]);
            $product->images()->attach($image->id);
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
        $brands     = auth()->user()->brands;
        $categories = Category::orderBy('name')->get();

        $variantsBySize = $product->variants->keyBy('symbol');

        return view('admin.edit-product', compact('product', 'brands', 'categories', 'variantsBySize'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);

        $sellerBrandIds = auth()->user()->brands->pluck('id')->toArray();

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'brand_id'    => ['required', 'integer', 'in:' . implode(',', $sellerBrandIds)],
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

    private function authorizeProduct(Product $product): void
    {
        $sellerBrandIds = auth()->user()->brands->pluck('id')->toArray();
        abort_unless(in_array($product->brand_id, $sellerBrandIds), 403);
    }
}
