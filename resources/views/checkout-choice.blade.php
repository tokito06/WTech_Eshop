@extends('layouts.app')

@section('title', 'Checkout')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endsection

@section('subnav')
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
<main class="cart-section">
    <div class="container d-flex justify-content-center">
        <div style="max-width:480px; width:100%; padding: 2rem 0;">

            <div class="cart-title">
                <span class="material-symbols-outlined">login</span>
                <h1>How would you like to continue?</h1>
            </div>

            <div class="d-flex flex-column gap-3 mt-4">
                <a href="{{ route('delivery') }}" class="cart-summary__btn">
                    Continue as guest
                </a>

                <a href="{{ route('checkout.to-login') }}" class="cart-summary__btn">
                    Log in
                </a>

                <a href="{{ route('checkout.to-register') }}" class="cart-summary__btn">
                    Create an account
                </a>
            </div>

        </div>
    </div>
</main>
@endsection
