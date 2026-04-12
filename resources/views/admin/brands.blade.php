@extends('layouts.admin')

@section('title', 'Admin — My Brands')

@section('content')

@php $isSuperAdmin = auth()->user()->isSuperAdmin(); @endphp
<main class="admin-main">
    <div class="container-fluid px-4 px-md-5">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="admin-page-title">{{ $isSuperAdmin ? 'Brands' : 'My brands' }}</h1>
        </div>

        <!-- Add brand form -->
        <form method="POST" action="{{ route('admin.brands.store') }}"
              class="d-flex gap-3 mb-4 align-items-start" style="max-width:500px">
            @csrf
            <div class="flex-grow-1">
                <input type="text" name="name" value="{{ old('name') }}"
                       class="admin-search w-100"
                       placeholder="New brand name…" required maxlength="150">
            </div>
            <button type="submit" class="admin-add-btn" style="white-space:nowrap">Add brand</button>
        </form>

        <!-- Brands table -->
        <div class="admin-table-card">
            <div class="admin-table-header"
                 style="grid-template-columns: 1fr {{ $isSuperAdmin ? 'clamp(120px,18%,200px)' : '' }} clamp(90px,12%,140px) 44px">
                <div>Brand</div>
                @if($isSuperAdmin)<div>Seller</div>@endif
                <div>Products</div>
                <div></div>
            </div>

            <div class="admin-table-body">
                @forelse($brands as $brand)
                <div class="admin-row"
                     style="grid-template-columns: 1fr {{ $isSuperAdmin ? 'clamp(120px,18%,200px)' : '' }} clamp(90px,12%,140px) 44px">
                    <div class="admin-cell-label">{{ $brand->name }}</div>
                    @if($isSuperAdmin)
                        <div class="admin-cell-muted">
                            {{ $brand->seller?->name ?? '—' }}
                        </div>
                    @endif
                    <div class="admin-cell-muted">{{ $brand->products_count }}</div>
                    <div>
                        @if($brand->products_count === 0)
                        <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}"
                              onsubmit="return confirm(@js('Delete brand \'' . $brand->name . '\' ?'))">
                            @csrf @method('DELETE')
                            <button type="submit" class="admin-edit-btn" title="Delete">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                        @else
                        <span title="Cannot delete — has products" style="cursor:not-allowed;opacity:0.35">
                            <span class="material-symbols-outlined" style="font-size:20px;color:#999">delete</span>
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-4 text-center" style="color:var(--dark-gray-color)">
                    @if($isSuperAdmin)
                        No brands in the system yet.
                    @else
                        You have no brands yet. Add your first brand above.
                    @endif
                </div>
                @endforelse
            </div>

        </div>
    </div>
</main>
@endsection
