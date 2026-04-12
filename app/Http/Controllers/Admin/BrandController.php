<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        if (auth()->user()->isSuperAdmin()) {
            $brands = Brand::withCount('products')
                ->with('seller')
                ->orderBy('name')
                ->get();
        } else {
            $brands = auth()->user()->brands()->withCount('products')->get();
        }

        return view('admin.brands', compact('brands'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:brands,name'],
        ]);

        Brand::create([
            'name'    => $request->name,
            'user_id' => auth()->user()->isSuperAdmin() ? null : auth()->id(),
        ]);

        return redirect()->route('admin.brands')->with('success', 'Brand created successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->count() > 0) {
            return redirect()->route('admin.brands')->with('error', 'Cannot delete brand with associated products.');
        }

        if (!auth()->user()->isSuperAdmin()) {
            abort_unless($brand->user_id === auth()->id(), 403);
        }

        $brand->delete();

        return redirect()->route('admin.brands')->with('success', 'Brand deleted.');
    }
}
