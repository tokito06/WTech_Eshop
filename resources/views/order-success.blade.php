@extends('layouts.app')

@section('title', 'Order placed')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/order-success.css') }}">
@endsection

@section('content')
<main class="success-section">
    <div class="container d-flex justify-content-center">

        <div class="success-card">

            <div class="success-icon">
                <span class="material-symbols-outlined">check</span>
            </div>

            <h1 class="success-title">Order placed!</h1>
            <p class="success-subtitle">
                Thank you for your purchase.<br>
                Your order is being processed and will be delivered soon.
            </p>

            <div class="success-order-info">
                <div class="success-order-info__row">
                    <span>Order number</span>
                    <span id="order-number">#WT-000001</span>
                </div>
                <div class="success-order-info__row">
                    <span>Items amount</span>
                    <span>100.99 €</span>
                </div>
                <div class="success-order-info__row">
                    <span>Delivery</span>
                    <span>Free</span>
                </div>
                <div class="success-order-info__row">
                    <span>Total paid</span>
                    <span>100.99 €</span>
                </div>
            </div>

            <div class="success-actions">
                <a href="{{ route('home') }}" class="success-btn">Continue shopping</a>
                <a href="{{ route('profile') }}" class="success-btn success-btn--secondary">View my orders</a>
            </div>

        </div>

    </div>
</main>
@endsection

@section('scripts')
<script>
    const num = Math.floor(100000 + Math.random() * 900000);
    document.getElementById('order-number').textContent = '#WT-' + num;
</script>
@endsection
