@extends('layouts.app')

@section('title', 'Product')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('subnav')
@php
    $gallery = ($product?->images && $product->images->count()) ? $product->images : collect();
    if ($gallery->isEmpty()) {
        $gallery = collect([(object) ['url' => asset('images/image_1.jpg')]]);
    }
    $firstVariant = $product?->variants?->first();
    $isFavourited = auth()->check()
        ? (bool) ($product?->is_favourited ?? $product?->isFavouritedBy(auth()->user()))
        : false;
@endphp
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('search', ['sex' => $product->sex]) }}">{{ ucfirst($product->sex ?? 'All') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('search', array_filter(['sex' => $product->sex, 'category_id' => $product->category_id])) }}">
                        {{ $product?->category?->name ?? 'Products' }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name ?? 'Product' }}</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<main class="product-section">
    <div class="container">
        <div class="row g-4 g-xl-5 align-items-start">

            <!-- Photos Carousel -->
            <div class="col-12 col-md-6">
                <div id="productCarousel" class="carousel slide product-carousel" data-bs-touch="true">
                    <div class="carousel-indicators">
                        @foreach ($gallery as $index => $image)
                            <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}" @class(['active' => $index === 0])></button>
                        @endforeach
                    </div>

                    <div class="carousel-inner">
                        @foreach ($gallery as $index => $image)
                            <div @class(['carousel-item', 'active' => $index === 0])>
                                <div class="carousel-slide"><img class="img__container" src="{{ $image->url }}" alt="{{ $product->name ?? 'Product' }} photo {{ $index + 1 }}"></div>
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>

                <!-- Thumbnails -->
                <div class="thumbnails-row">
                    @foreach ($gallery as $index => $image)
                        <div @class(['product__thumb', 'active' => $index === 0]) data-slide="{{ $index }}"><img src="{{ $image->url }}" alt="Thumbnail {{ $index + 1 }}"></div>
                    @endforeach
                </div>

                <div class="product__rating">☆ ☆ ☆ ☆ ☆</div>
            </div>

            <!-- Product info -->
            <div class="col-12 col-md-6">
                <div class="product__info">
                    <div class="product__badges">
                        <span class="product__category-badge">{{ $product?->category?->name ?? 'Category' }}</span>
                        <span class="product__category-badge">{{ $product?->brand?->name ?? 'Brand' }}</span>
                        <span class="product__category-badge">{{ ucfirst($product?->sex ?? 'Unspecified') }}</span>
                        <span class="product__stock-badge" id="stock-badge" hidden></span>
                    </div>
                    <h1>{{ $product->name ?? 'Product' }}</h1>

                    <p class="product__description">
                        {{ $product->description ?? 'Product description is not available.' }}
                    </p>

                    <div class="product__price" id="product-price">{{ number_format((float) ($firstVariant->price ?? 0), 2, ',', ' ') }} €</div>

                    <select class="product__size" id="product-size">
                        @forelse(($product?->variants ?? collect()) as $variant)
                            <option value="{{ $variant->id }}"
                                    data-price="{{ $variant->price }}"
                                    data-inventory="{{ $variant->inventory }}"
                                    @selected($loop->first)>
                                {{ $variant->symbol }}
                            </option>
                        @empty
                            <option value="">Size</option>
                        @endforelse
                    </select>

                    <div class="quantity__container">
                        <div class="quantity__label">Quantity</div>
                        <div class="quantity__selector">
                            <button class="quantity-selector__btn" id="qty-minus" aria-label="Decrease">−</button>
                            <input class="quantity-selector__input" id="qty-input" type="number" value="1" min="1" max="99" aria-label="Quantity">
                            <button class="quantity-selector__btn" id="qty-plus" aria-label="Increase">+</button>
                        </div>
                    </div>

                    <div class="product__actions">
                        <button
                            class="product__fav {{ $isFavourited ? 'liked' : '' }}"
                            title="{{ $isFavourited ? 'Remove from favourites' : 'Add to favourites' }}"
                            data-favourite-toggle
                            data-product-id="{{ $product->id }}"
                            data-favourited="{{ $isFavourited ? '1' : '0' }}"
                            aria-label="{{ $isFavourited ? 'Remove from favourites' : 'Add to favourites' }}"
                        >
                            <span class="material-symbols-outlined">{{ $isFavourited ? 'favorite' : 'favorite_border' }}</span>
                        </button>
                        <button
                            class="product__add"
                            id="add-to-bag"
                            data-variant-id="{{ $variantId ?? '' }}"
                            data-cart-add-url="{{ route('cart.add') }}"
                        >Add to bag</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<x-pop-out-modal
    modal-id="cartToastModal"
    title="Cart update"
    footer-class="justify-content-center"
>
    <div class="d-flex align-items-center gap-3">
        <span class="material-symbols-outlined fs-2">shopping_cart</span>
        <div>
            <div>Cart update: <strong id="toast-qty">1</strong> item(s)</div>
            <small id="toast-note" class="text-muted d-block"></small>
        </div>
    </div>

    <x-slot:buttons>
        <a href="{{ route('cart') }}" class="btn btn-outline-secondary">Go to cart</a>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
    </x-slot:buttons>
</x-pop-out-modal>
@endsection

