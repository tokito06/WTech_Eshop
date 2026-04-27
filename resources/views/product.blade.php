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
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
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
                        <span class="product__category-badge">{{ ucfirst($product?->sex ?? 'Unspecified') }}</span>                    </div>
                    <h1>{{ $product->name ?? 'Product' }}</h1>

                    <p class="product__description">
                        {{ $product->description ?? 'Product description is not available.' }}
                    </p>

                    <div class="product__price" id="product-price">{{ number_format((float) ($firstVariant->price ?? 0), 2, ',', ' ') }} €</div>

                    <select class="product__size" id="product-size">
                        @forelse(($product?->variants ?? collect()) as $variant)
                            <option value="{{ $variant->id }}" data-price="{{ $variant->price }}" @selected($loop->first)>{{ $variant->symbol }}</option>
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
                        <button class="product__add" id="add-to-bag" data-variant-id="{{ $variantId ?? '' }}">Add to bag</button>
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

@section('scripts')
<script>
    const carousel = document.getElementById('productCarousel');
    const thumbs = document.querySelectorAll('.product__thumb');
    const bsCarousel = bootstrap.Carousel.getOrCreateInstance(carousel);

    thumbs.forEach((thumb, i) => {
        thumb.addEventListener('click', () => { bsCarousel.to(i); });
    });

    carousel.addEventListener('slid.bs.carousel', e => {
        thumbs.forEach(t => t.classList.remove('active'));
        const idx = e.to < thumbs.length ? e.to : 0;
        thumbs[idx].classList.add('active');
    });

    const qtyInput = document.getElementById('qty-input');
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus  = document.getElementById('qty-plus');
    const sizeSelect = document.getElementById('product-size');
    const priceEl = document.getElementById('product-price');
    const addBtn   = document.getElementById('add-to-bag');
    const modalEl = document.getElementById('cartToastModal');
    const toastQtyEl = document.getElementById('toast-qty');
    const toastNoteEl = document.getElementById('toast-note');
    let cartModalTimer = null;

    function showCartToast(quantity, note = '') {
        toastQtyEl.textContent = String(Math.max(0, Number(quantity) || 0));
        toastNoteEl.textContent = note;

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        if (cartModalTimer) {
            clearTimeout(cartModalTimer);
        }

        cartModalTimer = setTimeout(() => modal.hide(), 2500);
    }

    function setQty(val) {
        const n = Math.max(1, Math.min(99, parseInt(val) || 1));
        qtyInput.value = n;
        qtyMinus.disabled = n <= 1;
    }

    function sanitizeQtyInput(value) {
        return String(value).replace(/[^0-9]/g, '').slice(0, 2);
    }

    qtyMinus.addEventListener('click', () => setQty(+qtyInput.value - 1));
    qtyPlus.addEventListener ('click', () => setQty(+qtyInput.value + 1));
    qtyInput.addEventListener('input', () => {
        // Allow temporary empty value while user is typing.
        qtyInput.value = sanitizeQtyInput(qtyInput.value);
    });
    qtyInput.addEventListener('blur', () => setQty(qtyInput.value));

    function syncVariantSelection() {
        const selected = sizeSelect?.selectedOptions?.[0];
        addBtn.dataset.variantId = selected?.value || '';

        if (priceEl && selected?.dataset?.price) {
            const price = Number(selected.dataset.price || 0);
            priceEl.textContent = `${price.toFixed(2).replace('.', ',')} €`;
        }
    }

    sizeSelect?.addEventListener('change', syncVariantSelection);
    syncVariantSelection();

    addBtn.addEventListener('click', async () => {
        @guest
            window.location.href = '{{ route('login') }}';
            return;
        @endguest

        const variantId = addBtn.dataset.variantId;
        setQty(qtyInput.value);
        const quantity = parseInt(qtyInput.value, 10) || 1;

        if (!variantId) {
            showCartToast(0, 'Variant is not available for this product.');
            return;
        }

        try {
            const response = await fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ variant_id: variantId, quantity }),
            });

            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to add item');
            }

            const addedQuantity = Number(data.added_quantity ?? quantity);
            const wasCapped = Boolean(data.capped) || addedQuantity < quantity;

            showCartToast(
                addedQuantity,
                wasCapped
                ? (data.message || `Only ${addedQuantity} item(s) were added due to stock limit.`)
                : ''
            );
        } catch (error) {
            showCartToast(0, error.message || 'Unable to add item to cart.');
        }
    });

    setQty(1);

    const stars = document.querySelector('.product__rating');
    let rating = 0;
    stars.addEventListener('click', e => {
        const rect = stars.getBoundingClientRect();
        rating = Math.ceil((e.clientX - rect.left) / rect.width * 5);
        stars.textContent = '★'.repeat(rating) + '☆'.repeat(5 - rating);
    });
</script>
@endsection
