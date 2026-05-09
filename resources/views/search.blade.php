@extends('layouts.app')

@section('title', 'Search results')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/search.css') }}">
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section("subnav")
<x-gender-subnav route-name="search" :active-sex="request('sex')" />

<!-- Search bar -->
<form class="search-bar" method="get" action="{{ route('search') }}" id="search-filters-form">
    <input type="hidden" id="search-query-hidden" name="q" value="{{ request('q') }}">

    @if(request()->filled('category_id'))
        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
    @endif

    @if(request()->filled('brand_id'))
        <input type="hidden" name="brand_id" value="{{ request('brand_id') }}">
    @endif

    @if(request()->filled('price_min'))
        <input type="hidden" name="price_min" value="{{ request('price_min') }}">
    @endif

    @if(request()->filled('price_max'))
        <input type="hidden" name="price_max" value="{{ request('price_max') }}">
    @endif

    @if(request()->filled('sex'))
        <input type="hidden" name="sex" value="{{ request('sex') }}">
    @endif

    @foreach((array) request()->query('sizes', []) as $selectedSize)
        <input type="hidden" name="sizes[]" value="{{ $selectedSize }}">
    @endforeach

    <div class="search-tag" id="search-tag">
        <input
            id="search-query-editable"
            class="search-tag__input"
            type="text"
            value="{{ request('q', '') }}"
            placeholder="All products"
            aria-label="Edit search query"
        >
        <button class="search-tag__close" id="search-tag-clear" type="button" aria-label="Clear search">✕</button>
    </div>

    <div class="sort-select">
        <span>Sort by</span>
        <select name="sort" aria-label="Sort by" onchange="this.form.submit()">
            <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>newest</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>lowest price</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>highest price</option>
        </select>
    </div>
</form>
@endsection

@section('content')
<main class="search-section">
    <div class="container">
        <div class="row g-4 align-items-start">

            <!-- Filters -->
            <aside class="col-12 col-md-3 col-lg-2">
                <button class="filters-toggle" id="filters-toggle" type="button">
                    <span>Filters</span>
                    <span class="material-symbols-outlined" id="filters-icon">expand_more</span>
                </button>

                <form class="filters filters-body" id="filters-body" method="get" action="{{ route('search') }}">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

                    @if(request()->filled('sex'))
                        <input type="hidden" name="sex" value="{{ request('sex') }}">
                    @endif

                    <div>
                        <div class="filter-title">Categories</div>
                        <ul class="filter-category">
                            <li class="{{ request()->filled('category_id') ? '' : 'active' }}">
                                <label>
                                    <input type="radio" name="category_id" value="" {{ request()->filled('category_id') ? '' : 'checked' }}>
                                    All
                                </label>
                            </li>
                            @foreach($categories as $category)
                                <li class="{{ (int) request('category_id') === (int) $category->id ? 'active' : '' }}">
                                    <label>
                                        <input type="radio" name="category_id" value="{{ $category->id }}" {{ (int) request('category_id') === (int) $category->id ? 'checked' : '' }}>
                                        {{ $category->name }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <div class="filter-title">Brand</div>
                        <ul class="filter-category">
                            <li class="{{ request()->filled('brand_id') ? '' : 'active' }}">
                                <label>
                                    <input type="radio" name="brand_id" value="" {{ request()->filled('brand_id') ? '' : 'checked' }}>
                                    All
                                </label>
                            </li>
                            @foreach($brands as $brand)
                                <li class="{{ (int) request('brand_id') === (int) $brand->id ? 'active' : '' }}">
                                    <label>
                                        <input type="radio" name="brand_id" value="{{ $brand->id }}" {{ (int) request('brand_id') === (int) $brand->id ? 'checked' : '' }}>
                                        {{ $brand->name }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <div class="filter-title">Price</div>
                        <div class="price-range">
                            <div class="price-badge">
                                <input type="number" id="price-min" name="price_min" value="{{ request('price_min') }}" min="0" max="999999" aria-label="Min price">
                                <span>€</span>
                            </div>
                            <span class="price-range__dash">—</span>
                            <div class="price-badge">
                                <input type="number" id="price-max" name="price_max" value="{{ request('price_max') }}" min="0" max="999999" aria-label="Max price">
                                <span>€</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="filter-title">Sizes</div>
                        <div class="size-btns">
                            @foreach($sizes as $size)
                                @php $isSelected = in_array($size, (array) request()->query('sizes', []), true); @endphp
                                <label class="size-btn {{ $isSelected ? 'active' : '' }}">
                                    <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ $isSelected ? 'checked' : '' }}>
                                    {{ $size }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark">Apply</button>
                        <a class="btn btn-outline-secondary" href="{{ route('search', request()->filled('q') ? ['q' => request('q')] : []) }}">Clear filters</a>
                    </div>
                </form>
            </aside>

            <!-- Search items -->
            <div class="col-12 col-md-9 col-lg-10">
                <div class="row g-3" id="products-grid">
                    @forelse($products as $product)
                        <div class="col-6 col-lg-4 col-xxl-3">
                            <x-product-card :product="$product" :show-sizes="true" />
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted mb-0">No products found.</p>
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                    <nav class="search-pagination mt-4" aria-label="Search pagination">
                        @if($products->onFirstPage())
                            <span class="search-pagination__btn is-disabled" aria-disabled="true">Prev</span>
                        @else
                            <a class="search-pagination__btn" href="{{ $products->previousPageUrl() }}">Prev</a>
                        @endif

                        @php
                            $lastPage = max(1, $products->lastPage());
                            $currentPage = min(max(1, $products->currentPage()), $lastPage);
                            $start = max(1, $currentPage - 1);
                            $end = min($lastPage, $currentPage + 1);
                        @endphp
                            @foreach($products->getUrlRange($start, $end) as $page => $url)
                                @if($page == $currentPage)
                                <span class="search-pagination__btn is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="search-pagination__btn" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($products->hasMorePages())
                            <a class="search-pagination__btn" href="{{ $products->nextPageUrl() }}">Next</a>
                        @else
                            <span class="search-pagination__btn is-disabled" aria-disabled="true">Next</span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

