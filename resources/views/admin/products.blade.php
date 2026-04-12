@extends('layouts.admin')

@section('title', 'Admin — Products')

@section('content')
<main class="admin-main">
    <div class="container-fluid px-4 px-md-5">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="admin-page-title">Products</h1>
            <a href="{{ route('admin.products.create') }}" class="admin-add-btn clear-link">Add a product</a>
        </div>

        <!-- Table card -->
        <div class="admin-table-card">

            <!-- Toolbar -->
            <div class="admin-toolbar">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.products') }}"
                       class="admin-filter-btn {{ !request('status') ? 'active' : '' }}">All</a>
                    <a href="{{ route('admin.products', array_filter(['status' => 'active', 'brand_id' => request('brand_id')])) }}"
                       class="admin-filter-btn {{ request('status') === 'active' ? 'active' : '' }}">Active</a>
                    <a href="{{ route('admin.products', array_filter(['status' => 'archived', 'brand_id' => request('brand_id')])) }}"
                       class="admin-filter-btn {{ request('status') === 'archived' ? 'active' : '' }}">Archived</a>
                </div>

                @if(auth()->user()->isSuperAdmin() && $allBrands->isNotEmpty())
                <div class="ms-auto">
                    <select class="admin-search" style="width:auto;padding:5px 12px;cursor:pointer"
                            onchange="window.location = '{{ route('admin.products') }}?brand_id=' + this.value + '{{ request('status') ? '&status=' . request('status') : '' }}'">
                        <option value="">All brands</option>
                        @foreach($allBrands as $b)
                            <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <!-- Table header -->
            <div class="admin-table-header">
                <div class="col-check"></div>
                <div class="col-img"></div>
                <div class="col-name">Product</div>
                <div class="col-status">Status</div>
                <div class="col-inventory">Inventory</div>
                <div class="col-type">Category</div>
                <div class="col-vendor">Brand</div>
                <div class="col-action"></div>
            </div>

            <!-- Rows -->
            <div class="admin-table-body" id="products-list">

                @forelse($products as $product)
                <div class="admin-row">
                    <div class="col-check">
                        <input type="radio" class="admin-radio" name="product-select">
                    </div>
                    <div class="col-img">
                        <div class="admin-product-img">
                            @if($product->firstImageUrl)
                                <img class="img__container" src="{{ $product->firstImageUrl }}" alt="{{ $product->name }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <span class="material-symbols-outlined" style="color:#aaa;font-size:18px">image</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-name admin-cell-label">{{ $product->name }}</div>
                    <div class="col-status">
                        <span class="admin-status-badge admin-status-badge--{{ $product->status }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </div>
                    <div class="col-inventory admin-cell-muted">
                        {{ $product->variants->sum('inventory') }}
                    </div>
                    <div class="col-type admin-cell-muted">{{ $product->category?->name ?? '—' }}</div>
                    <div class="col-vendor admin-cell-muted">{{ $product->brand?->name ?? '—' }}</div>
                    <div class="col-action d-flex gap-1">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="admin-edit-btn clear-link" title="Edit">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                              onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="admin-edit-btn" title="Delete">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center" style="color:var(--dark-gray-color)">
                    No products yet.
                    <a href="{{ route('admin.products.create') }}">Add your first product →</a>
                </div>
                @endforelse

            </div>

            @if($products->hasPages())
            <div class="d-flex justify-content-center py-3">
                {{ $products->links() }}
            </div>
            @endif

        </div>
    </div>
</main>
@endsection

@section('scripts')
<style>
.admin-filter-btn { text-decoration: none; color: #000; }
.admin-status-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: clamp(0.7rem, 0.85vw, 0.95rem);
    font-weight: 600;
}
.admin-status-badge--active   { background: #d1fae5; color: #065f46; }
.admin-status-badge--archived { background: var(--gray-color); color: var(--dark-gray-color); }
</style>
@endsection
