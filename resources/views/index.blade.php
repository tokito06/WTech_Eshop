@extends('layouts.app')

@section('title', 'Home')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('content')
<main>

    <!-- Promo banner -->
    <section class="home-banner-section">
        <div class="container">
            <div class="home-banner-scroll" id="home-banner-scroll" aria-label="Promotions">
                @forelse($banners as $banner)
                <a class="home-banner-card clear-link" href="{{ route('search') }}">
                    @if($banner->image)
                    <div class="home-banner-card__media">
                        <img class="img__container" src="{{ $banner->image->url }}" alt="{{ $banner->name }}">
                    </div>
                    @endif
                    <div class="home-banner-card__content">
                        <p class="home-banner-card__eyebrow">Featured</p>
                        <h2>{{ $banner->name }}</h2>
                        <p>{{ $banner->description }}</p>
                    </div>
                </a>
                @empty
                <a class="home-banner-card clear-link" href="{{ route('search') }}">
                    <div class="home-banner-card__content">
                        <p class="home-banner-card__eyebrow">New Collection</p>
                        <h2>Fresh Arrivals</h2>
                        <p>Discover trending items and complete your look.</p>
                    </div>
                </a>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Categories Scroll -->
    <section class="home-section categories-section">
        <div class="container">
            <h2 class="section-title">Explore our categories</h2>
            <div class="categories-wrapper">
                <button class="cat-arrow cat-arrow--prev" id="cat-prev" aria-label="Previous">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="cat-arrow cat-arrow--next" id="cat-next" aria-label="Next">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
                <div class="categories-inner">
                    <div class="categories-scroll" id="cat-scroll">
                        @foreach($categories as $category)
                        <a class="cat-card clear-link" href="{{ route('search') }}?category_id={{ $category->id }}">
                            <div class="cat-card__img">
                                <span class="material-symbols-outlined cat-card__icon" aria-hidden="true">{{ $category->icon }}</span>
                            </div>
                            <span class="cat-card__label">{{ $category->name }}</span>
                        </a>
                        @endforeach
                    </div>
                    <div class="cat-dots">
                        <button class="cat-dot active" data-page="0"></button>
                        <button class="cat-dot"        data-page="1"></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New for Men -->
    @if($menProducts->isNotEmpty())
    <section class="home-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title mb-0">New for Men</h2>
                <a href="{{ route('search') }}?sex=men" class="explore-link">
                    See all <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="row g-3 g-md-4">
                @foreach($menProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <x-product-card :product="$product" :show-sizes="false" />
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- New for Women -->
    @if($womenProducts->isNotEmpty())
    <section class="home-section home-section--soft">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title mb-0">New for Women</h2>
                <a href="{{ route('search') }}?sex=women" class="explore-link">
                    See all <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="row g-3 g-md-4">
                @foreach($womenProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <x-product-card :product="$product" :show-sizes="false" />
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Trending now -->
    @if($trendingProducts->isNotEmpty())
    <section class="home-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title mb-0">Trending now</h2>
                <a href="{{ route('search') }}" class="explore-link">
                    See all <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="row g-3 g-md-4">
                @foreach($trendingProducts as $product)
                <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                    <x-product-card :product="$product" :show-sizes="false" />
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</main>
@endsection

