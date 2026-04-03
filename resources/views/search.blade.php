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
<div class="search-bar">
    <div class="search-tag">
        "Searched text"
        <button class="search-tag__close" id="clear-search" aria-label="Clear search">✕</button>
    </div>

    <div class="sort-select">
        <span>Sort by</span>
        <select aria-label="Sort by">
            <option>lowest price</option>
            <option>highest price</option>
            <option>newest</option>
            <option>most popular</option>
        </select>
        <span class="material-symbols-outlined" style="font-size:18px; color:var(--dark-gray-color);">expand_more</span>
    </div>
</div>
@endsection

@section('content')
<main class="search-section">
    <div class="container">
        <div class="row g-4 align-items-start">

            <!-- Filters -->
            <aside class="col-12 col-md-3 col-lg-2">
                <button class="filters-toggle" id="filters-toggle">
                    <span>Filters</span>
                    <span class="material-symbols-outlined" id="filters-icon">expand_more</span>
                </button>

                <div class="filters filters-body" id="filters-body">
                    <div>
                        <div class="filter-title">Categories</div>
                        <ul class="filter-category">
                            <li class="active">Category 1</li>
                            <li>Category 2</li>
                            <li>Category 3</li>
                        </ul>
                    </div>

                    <div>
                        <div class="filter-title">Brand</div>
                        <ul class="filter-category">
                            <li class="active">All</li>
                            <li>Nike</li>
                            <li>Adidas</li>
                            <li>Puma</li>
                            <li>New Balance</li>
                            <li>Zara</li>
                        </ul>
                    </div>

                    <div>
                        <div class="filter-title">Price</div>
                        <div class="price-range">
                            <div class="price-badge">
                                <input type="number" id="price-min" value="0" min="0" max="100" aria-label="Min price">
                                <span>€</span>
                            </div>
                            <span class="price-range__dash">—</span>
                            <div class="price-badge">
                                <input type="number" id="price-max" value="100" min="0" max="9999" aria-label="Max price">
                                <span>€</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="filter-title">Sizes</div>
                        <div class="size-btns">
                            <button class="size-btn active">M</button>
                            <button class="size-btn">S</button>
                            <button class="size-btn">L</button>
                            <button class="size-btn">XL</button>
                            <button class="size-btn">XS</button>
                            <button class="size-btn">XXL</button>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Search items -->
            <div class="col-12 col-md-9 col-lg-10">
                <div class="row g-3" id="products-grid">

                    @for ($i = 0; $i < 8; $i++)
                    <div class="col-6 col-lg-4 col-xxl-3">
                        <div class="product-card">
                            <button class="product-card__fav"><span class="material-symbols-outlined">favorite</span></button>
                            <a href="{{ route('product') }}" class="clear-link">
                                <div class="product-card__img">
                                    <img src="{{ asset('images/image_' . ($i % 3 + 1) . '.jpg') }}" alt="Product image">
                                    <div class="product-card__sizes">
                                        <span class="product-card__size-tag">S</span>
                                        <span class="product-card__size-tag">M</span>
                                        <span class="product-card__size-tag">L</span>
                                        <span class="product-card__size-tag">XL</span>
                                    </div>
                                </div>
                                <div class="product-card__body">
                                    <h3 class="product-card__name">Product Name</h3>
                                    <p class="product-card__desc">Product description goes here.</p>
                                    <span class="product-card__price">19,99 €</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endfor

                </div>

                <!-- Pagination -->
                <div class="pagination-bar">
                    <button class="page-arrow" aria-label="Previous page">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <span class="page-info">Page 1 … 426</span>
                    <button class="page-arrow" aria-label="Next page">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.category-nav .nav-link').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            document.querySelectorAll('.category-nav .nav-link').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });

    document.getElementById('clear-search').addEventListener('click', () => {
        const tag = document.querySelector('.search-tag');
        tag.style.opacity = '0.4';
        tag.style.pointerEvents = 'none';
    });

    document.querySelectorAll('.filter-category li').forEach(li => {
        li.addEventListener('click', () => {
            document.querySelectorAll('.filter-category li').forEach(l => l.classList.remove('active'));
            li.classList.add('active');
        });
    });

    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', () => btn.classList.toggle('active'));
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

    filtersToggle.addEventListener('click', () => {
        const isOpen = filtersBody.classList.toggle('open');
        filtersIcon.textContent = isOpen ? 'expand_less' : 'expand_more';
    });

    const priceMin = document.getElementById('price-min');
    const priceMax = document.getElementById('price-max');
    priceMin.addEventListener('change', () => { if (+priceMin.value > +priceMax.value) priceMax.value = priceMin.value; });
    priceMax.addEventListener('change', () => { if (+priceMax.value < +priceMin.value) priceMin.value = priceMax.value; });
</script>
@endsection
