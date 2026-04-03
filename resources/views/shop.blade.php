@extends('layouts.app')

@section('title', 'Shop')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/main_page.css') }}">
@endsection

@section('subnav')
<!-- Category Nav -->
<nav class="category-nav">
    <ul class="nav d-flex flex-nowrap overflow-auto" style="scrollbar-width:none;">
        <li class="nav-item"><a class="nav-link active" href="{{ route('search') }}">Men</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('search') }}">Women</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('search') }}">Kids</a></li>
    </ul>
</nav>
@endsection

@section('content')
<main>
    <div class="container py-4">

        <!-- Search input -->
        <div class="shop-toolbar">
            <input class="shop-search" type="search" placeholder="Type your query here...." aria-label="Search products">
            <a href="{{ route('search') }}" class="shop-search-btn" aria-label="Search products" title="Search">
                <span class="material-symbols-outlined" aria-hidden="true">search</span>
            </a>
        </div>

        <!-- Product Grid -->
        <div class="row g-3 g-md-4">
            <div class="col-6 col-lg-4 col-xxl-3">
                <div class="product-card">
                    <button class="product-card__fav"><span class="material-symbols-outlined">favorite</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img">
                            <img src="{{ asset('images/image_1.jpg') }}" alt="Product image">
                            <div class="product-card__sizes">
                                <span class="product-card__size-tag">S</span>
                                <span class="product-card__size-tag">M</span>
                                <span class="product-card__size-tag">L</span>
                                <span class="product-card__size-tag">XL</span>
                            </div>
                        </div>
                        <div class="product-card__body">
                            <h3 class="product-card__name">Super View Glasses</h3>
                            <p class="product-card__desc">UV-protective lenses with a lightweight frame. Wide field of view for everyday outdoor wear.</p>
                            <span class="product-card__price">19,99 €</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-lg-4 col-xxl-3">
                <div class="product-card">
                    <button class="product-card__fav"><span class="material-symbols-outlined">favorite</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img">
                            <img src="{{ asset('images/image_2.jpg') }}" alt="Product image">
                            <div class="product-card__sizes">
                                <span class="product-card__size-tag">S</span>
                                <span class="product-card__size-tag">M</span>
                                <span class="product-card__size-tag">L</span>
                                <span class="product-card__size-tag">XL</span>
                            </div>
                        </div>
                        <div class="product-card__body">
                            <h3 class="product-card__name">Red Jacket</h3>
                            <p class="product-card__desc">Bold red jacket with a modern slim cut. Water-resistant fabric, perfect for spring and autumn.</p>
                            <span class="product-card__price">104,50 €</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-lg-4 col-xxl-3">
                <div class="product-card">
                    <button class="product-card__fav"><span class="material-symbols-outlined">favorite</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img">
                            <img src="{{ asset('images/image_3.jpg') }}" alt="Product image">
                            <div class="product-card__sizes">
                                <span class="product-card__size-tag">S</span>
                                <span class="product-card__size-tag">M</span>
                                <span class="product-card__size-tag">L</span>
                                <span class="product-card__size-tag">XL</span>
                            </div>
                        </div>
                        <div class="product-card__body">
                            <h3 class="product-card__name">White T-shirt</h3>
                            <p class="product-card__desc">Classic 100% cotton tee with a relaxed fit. Soft, breathable and versatile for any casual look.</p>
                            <span class="product-card__price">39,00 €</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-lg-4 col-xxl-3">
                <div class="product-card">
                    <button class="product-card__fav"><span class="material-symbols-outlined">favorite</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img">
                            <img src="{{ asset('images/image_1.jpg') }}" alt="Product image">
                            <div class="product-card__sizes">
                                <span class="product-card__size-tag">S</span>
                                <span class="product-card__size-tag">M</span>
                                <span class="product-card__size-tag">L</span>
                                <span class="product-card__size-tag">XL</span>
                            </div>
                        </div>
                        <div class="product-card__body">
                            <h3 class="product-card__name">Super View Glasses</h3>
                            <p class="product-card__desc">Polarised lenses in a sleek frame. Blocks glare and protects eyes on sunny days.</p>
                            <span class="product-card__price">12,99 €</span>
                        </div>
                    </a>
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

    document.querySelectorAll('.product-card__fav').forEach(btn => {
        btn.addEventListener('click', () => btn.classList.toggle('liked'));
    });

    document.querySelectorAll('.product-card__sizes').forEach(group => {
        group.addEventListener('click', e => e.preventDefault());
        group.querySelectorAll('.product-card__size-tag').forEach(badge => {
            badge.addEventListener('click', e => {
                e.stopPropagation();
                e.preventDefault();
                group.querySelectorAll('.product-card__size-tag').forEach(b => b.classList.remove('active'));
                badge.classList.add('active');
            });
        });
    });
</script>
@endsection
