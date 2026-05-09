@extends('layouts.app')

@section('title', 'Cart')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endsection

@section('subnav')
<!-- Checkout Steps -->
<div class="checkout-steps">
    <div class="container-fluid">
        <ol class="checkout-steps__list">
            <li class="checkout-steps__item active"><span class="checkout-steps__num">1</span>Cart</li>
            <li class="checkout-steps__item"><span class="checkout-steps__num">2</span>Delivery</li>
            <li class="checkout-steps__item"><span class="checkout-steps__num">3</span>Payment</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<main class="cart-section">
    <div class="container">

        @php
            $cartItems = $items ?? collect();
            $itemsTotal = $cart?->total ?? 0;
        @endphp

        <div class="cart-title">
            <span class="material-symbols-outlined">shopping_cart</span>
            <h1>Cart</h1>
        </div>

        <div class="row g-4 align-items-start">

            <!-- Goods list -->
            <section class="col-12 col-lg-8">

                <div class="cart-empty" id="cart-empty" style="{{ $cartItems->isEmpty() ? '' : 'display: none;' }}">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <p>Your cart is empty</p>
                </div>

                <div
                    id="cart-items-list"
                    data-update-url-template="{{ route('cart.update', ['item' => '__ITEM_ID__']) }}"
                    data-remove-url-template="{{ route('cart.remove', ['item' => '__ITEM_ID__']) }}"
                >
                    @if($cartItems->isNotEmpty())
                        <h2 class="cart-items__title">Items</h2>
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->variant?->product;
                                $image = $product?->images?->first()?->url ?? asset('images/image_1.jpg');
                                $size = $item->variant?->symbol ?? 'N/A';
                                $price = (float) ($item->amount ?? 0);
                                $inventory = (int) ($item->variant?->inventory ?? 0);
                                $quantityLimit = $inventory > 0 ? min(99, $inventory) : 99;
                                $quantityMaxLength = strlen((string) $quantityLimit);
                            @endphp
                            <div class="cart-item" data-id="{{ $item->id }}">
                                <label class="cart-item__checkbox">
                                    <input type="checkbox" aria-label="Select item">
                                    <span class="cart-item__checkbox-box"></span>
                                </label>
                                <a class="cart-item__link" href="{{ $product ? route('product', ['product' => $product->id]) : route('product') }}" aria-label="View {{ $product?->name ?? 'product' }}">
                                    <div class="cart-item__image">
                                        <img class="img__container" src="{{ $image }}" alt="{{ $product?->name ?? 'Product' }} image">
                                    </div>
                                    <div class="cart-item__info">
                                        <h3>{{ $product?->name ?? 'Product' }}</h3>
                                        <p>{{ $product?->description ?? '' }}</p>
                                        <div class="cart-item__meta">
                                            <span class="cart-item__size-badge">{{ $size }}</span>
                                            <span class="cart-item__price">{{ number_format($price, 2, '.', '') }} €</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="cart-item__controls">
                                    <button type="button" class="cart-item__ctrl-btn" data-action="dec" aria-label="Decrease">−</button>
                                    <input
                                        class="cart-item__count-input"
                                        type="text"
                                        maxlength="{{ $quantityMaxLength }}"
                                        inputmode="numeric"
                                        value="{{ $item->quantity }}"
                                        data-max="{{ $quantityLimit }}"
                                        aria-label="Quantity for {{ $product?->name ?? 'product' }}"
                                    >
                                    <button type="button" class="cart-item__ctrl-btn" data-action="inc" aria-label="Increase">+</button>
                                </div>
                                <button class="cart-item__delete" aria-label="Remove item" title="Remove">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>

            <!-- Cart summary -->
            <section class="col-12 col-lg-4 cart-sticky">
                <div class="cart-summary">
                    <h2 class="cart-summary__title">Cart summary</h2>
                    <div class="cart-summary__amounts">
                        <div class="cart-summary__amount">
                            <span>Items amount</span>
                            <span id="summary-items">{{ number_format($itemsTotal, 2, '.', '') }} €</span>
                        </div>
                        <div class="cart-summary__amount">
                            <span>Delivery</span>
                            <small>from 0 €</small>
                        </div>
                        <div class="cart-summary__amount">
                            <span>Total</span>
                            <strong class="cart-summary__total" id="summary-total">{{ number_format($itemsTotal, 2, '.', '') }} €</strong>
                        </div>
                    </div>
                    <a href="{{ route('checkout') }}" class="cart-summary__btn {{ $cartItems->isEmpty() ? 'cart-summary__btn--disabled' : '' }}" id="checkout-btn">Go to checkout</a>
                </div>
            </section>

        </div>
    </div>
</main>

<div class="toast-container cart-toast-container">
    <div id="cartStockToast" class="toast align-items-center border-0 cart-stock-toast" role="alert" aria-live="assertive">
        <div class="cart-stock-toast__content">
            <span class="material-symbols-outlined">info</span>
            <span id="cart-stock-toast-message">Only 1 item is available.</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<script type="application/json" id="cart-items-data">
@json($cartItems->values())
</script>
@endsection
