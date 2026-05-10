@extends('layouts.app')

@section('title', 'Delivery')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
<link rel="stylesheet" href="{{ asset('css/delivery.css') }}">
@endsection

@section('subnav')
<!-- Checkout Steps -->
<div class="checkout-steps">
    <div class="container-fluid">
        <ol class="checkout-steps__list">
            <li class="checkout-steps__item done"><a href="{{ route('cart') }}"><span class="checkout-steps__num">1</span>Cart</a></li>
            <li class="checkout-steps__item active"><span class="checkout-steps__num">2</span>Delivery</li>
            <li class="checkout-steps__item"><span class="checkout-steps__num">3</span>Payment</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@php
    $selectedMethodId = old('delivery_method_id', $selectedDeliveryMethodId ?? '');
@endphp
<main class="delivery-section">
    <div class="container">
        <div class="row g-4 g-xl-5 align-items-start">

            <!-- Left: delivery service + form -->
            <section class="col-12 col-lg-7">

                <div class="delivery-title">
                    <span class="material-symbols-outlined">local_shipping</span>
                    <h1>Choose delivery service</h1>
                </div>

                <div class="delivery-services" id="delivery-services">
                    @foreach($deliveryMethods as $method)
                    <label class="service-card {{ (string) $selectedMethodId === (string) $method->id ? 'service-card--selected' : '' }}">
                        <input type="radio" name="delivery_method_id" value="{{ $method->id }}" data-price="{{ $method->price }}" {{ (string) $selectedMethodId === (string) $method->id ? 'checked' : '' }}>
                        <span class="service-card__name">{{ $method->name }}</span>
                        <span class="service-card__desc">{{ $method->brief }}</span>
                        <span class="service-card__meta">
                            <span class="service-card__time"><span class="material-symbols-outlined">schedule</span>{{ $method->expected_time }}-{{ $method->expected_time + 2 }} days</span>
                            <span class="service-card__price">{{ $method->price == 0 ? 'Free' : 'from ' . number_format($method->price, 2) . ' €' }}</span>
                        </span>
                    </label>
                    @endforeach
                </div>

                <p class="delivery-services__error" id="service-error">Please select a delivery service.</p>

                <div class="delivery-title delivery-title--form">
                    <span class="material-symbols-outlined">person</span>
                    <h2>Enter delivery information</h2>
                </div>

                <form class="delivery-form" id="delivery-form" action="{{ route('delivery.store') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" id="delivery-method-id" name="delivery_method_id" value="{{ $selectedMethodId }}">
                    @guest
                    <div class="d-flex flex-column flex-md-row gap-2 align-items-md-end">
                        <div class="flex-grow-1">
                            <input
                                class="delivery-input"
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Email"
                                value="{{ old('email', $prefill['email'] ?? '') }}"
                                autocomplete="email"
                                inputmode="email"
                                required
                            >
                            @error('email')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>
                        <div class="d-flex gap-2">
                            <a
                                href="{{ route('checkout.to-login') }}"
                                data-base-href="{{ route('checkout.to-login') }}"
                                id="checkout-login-link"
                                class="cart-summary__btn delivery-auth-btn delivery-auth-btn--ghost"
                            >Login</a>
                            <a
                                href="{{ route('checkout.to-register') }}"
                                data-base-href="{{ route('checkout.to-register') }}"
                                id="checkout-register-link"
                                class="cart-summary__btn delivery-auth-btn"
                            >Register</a>
                        </div>
                    </div>
                    @endguest
                    @auth
                        <input type="hidden" name="email" value="{{ old('email', $prefill['email'] ?? '') }}">
                    @endauth
                    <div class="delivery-form__row">
                        <input class="delivery-input" type="text" id="name" name="first_name" placeholder="First name" value="{{ old('first_name', $prefill['first_name'] ?? '') }}" required>
                        <input class="delivery-input" type="text" id="surname" name="last_name" placeholder="Last name" value="{{ old('last_name', $prefill['last_name'] ?? '') }}" required>
                    </div>
                    <input class="delivery-input" type="tel" id="phone" name="phone_number" placeholder="Phone number" value="{{ old('phone_number', $prefill['phone_number'] ?? '') }}" required inputmode="tel">
                    <input class="delivery-input" type="text" id="street" name="street" placeholder="Street and house number" value="{{ old('street', $prefill['street'] ?? '') }}" required>
                    <div class="delivery-form__row">
                        <input class="delivery-input" type="text" id="city" name="city" placeholder="City" value="{{ old('city', $prefill['city'] ?? '') }}" required>
                        <input class="delivery-input" type="text" id="zip" name="post_code" placeholder="ZIP code" value="{{ old('post_code', $prefill['post_code'] ?? '') }}" required inputmode="numeric">
                    </div>
                    <input class="delivery-input" type="text" id="country" name="country" placeholder="Country" value="{{ old('country', $prefill['country'] ?? '') }}" required>
                </form>
            </section>

            <!-- Right: cart summary -->
            <section class="col-12 col-lg-5 cart-sticky">
                <div class="cart-summary">
                    <h2 class="cart-summary__title">Cart summary</h2>
                    <div class="cart-summary__amounts">
                        <div class="cart-summary__amount">
                            <span>Items amount</span>
                            <span id="summary-items" data-items-total="{{ number_format($itemsTotal ?? 0, 2, '.', '') }}">{{ number_format($itemsTotal ?? 0, 2, '.', '') }} €</span>
                        </div>
                        <div class="cart-summary__amount">
                            <span>Delivery amount</span>
                            <small id="summary-delivery">
                                @if($deliveryPrice !== null)
                                    {{ $deliveryPrice == 0 ? 'Free' : number_format($deliveryPrice, 2, '.', '') . ' €' }}
                                @else
                                    from {{ number_format($minDeliveryPrice ?? 0, 2, '.', '') }} €
                                @endif
                            </small>
                        </div>
                        <div class="cart-summary__amount">
                            <span>Total</span>
                            <strong class="cart-summary__total" id="summary-total">{{ number_format($grandTotal ?? 0, 2, '.', '') }} €</strong>
                        </div>
                    </div>
                    <button class="cart-summary__btn" id="payment-btn">Payment</button>
                </div>
            </section>

        </div>
    </div>
</main>
@endsection

