@extends('layouts.app')

@section('title', 'Search results')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endsection

@section('subnav')
<!-- Category Nav -->
<nav class="category-nav">
    <ul class="nav" style="scrollbar-width:none;">
        <li class="nav-item"><a class="nav-link active" href="#">Men</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Women</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Kids</a></li>
    </ul>
</nav>

<!-- Search bar -->
<form class="search-bar" method="get" action="{{ route('search') }}" id="search-filters-form">
    <input type="hidden" name="q" value="{{ request('q') }}">

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

    <div class="search-tag">
        "{{ request('q', 'All products') }}"
        <a class="search-tag__close" href="{{ route('search', array_merge(request()->except(['q', 'page']), request()->filled('sex') ? ['sex' => request('sex')] : [])) }}" aria-label="Clear search">✕</a>
    </div>

    <div class="sort-select">
        <span>Sort by</span>
        <select name="sort" aria-label="Sort by" onchange="this.form.submit()">
            <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>newest</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>lowest price</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>highest price</option>
        </select>
        <span class="material-symbols-outlined" style="font-size:18px; color:var(--dark-gray-color);">expand_more</span>
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
                        @php
                            $image = $product->images->first();
                            $imageUrl = $image ? $image->url : asset('images/image_1.jpg');
                            $sizes = $product->variants->pluck('symbol')->take(4);
                            $price = $product->variants->min('price') ?? 0;
                        @endphp
                        <div class="col-6 col-lg-4 col-xxl-3">
                            <div class="product-card">
                                <button class="product-card__fav"><span class="material-symbols-outlined">favorite</span></button>
                                <a href="{{ route('product', $product) }}" class="clear-link">
                                    <div class="product-card__img">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }} image">
                                        <div class="product-card__sizes">
                                            @foreach($sizes as $size)
                                                <span class="product-card__size-tag">{{ $size }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="product-card__body">
                                        <h3 class="product-card__name">{{ $product->name }}</h3>
                                        <p class="product-card__desc">{{ \Illuminate\Support\Str::limit($product->description, 120) }}</p>
                                        <span class="product-card__price">{{ number_format((float) $price, 2, ',', ' ') }} €</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted mb-0">No products found.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    const filtersForm = document.getElementById('filters-body');

    const submitFilters = () => {
        if (!filtersForm) {
            return;
        }

        if (typeof filtersForm.requestSubmit === 'function') {
            filtersForm.requestSubmit();
            return;
        }

        filtersForm.submit();
    };

    document.querySelectorAll('.filter-category').forEach(list => {
        const radios = list.querySelectorAll('input[type="radio"]');

        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                list.querySelectorAll('li').forEach(item => item.classList.remove('active'));
                radio.closest('li')?.classList.add('active');
                submitFilters();
            });
        });
    });

    document.querySelectorAll('.size-btn input[type="checkbox"]').forEach(input => {
        input.addEventListener('change', () => {
            input.closest('.size-btn').classList.toggle('active', input.checked);
            submitFilters();
        });
    });

    document.querySelectorAll('.product-card__fav').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('liked');
            const icon = btn.querySelector('.material-symbols-outlined');
            icon.style.fontVariationSettings = btn.classList.contains('liked') ? "'FILL' 1" : "'FILL' 0";
        });
    });

    const filtersToggle = document.getElementById('filters-toggle');
    const filtersBody   = document.getElementById('filters-body');
    const filtersIcon   = document.getElementById('filters-icon');

    if (filtersToggle && filtersBody && filtersIcon) {
        filtersToggle.addEventListener('click', () => {
            const isOpen = filtersBody.classList.toggle('open');
            filtersIcon.textContent = isOpen ? 'expand_less' : 'expand_more';
        });
    }

    const priceMin = document.getElementById('price-min');
    const priceMax = document.getElementById('price-max');
    if (priceMin && priceMax) {
        const normalizePriceRange = () => {
            if (priceMin.value !== '' && priceMax.value !== '' && +priceMin.value > +priceMax.value) {
                priceMax.value = priceMin.value;
            }
        };

        const submitOnPriceBlur = () => {
            window.setTimeout(() => {
                const activeElement = document.activeElement;

                if (activeElement === priceMin || activeElement === priceMax) {
                    return;
                }

                normalizePriceRange();
                submitFilters();
            }, 0);
        };

        const submitOnEnter = event => {
            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();
            normalizePriceRange();
            submitFilters();
        };

        priceMin.addEventListener('change', normalizePriceRange);
        priceMax.addEventListener('change', () => {
            if (priceMin.value !== '' && priceMax.value !== '' && +priceMax.value < +priceMin.value) {
                priceMin.value = priceMax.value;
            }
        });

        priceMin.addEventListener('blur', submitOnPriceBlur);
        priceMax.addEventListener('blur', submitOnPriceBlur);
        priceMin.addEventListener('keydown', submitOnEnter);
        priceMax.addEventListener('keydown', submitOnEnter);
    }
</script>
@endsection
