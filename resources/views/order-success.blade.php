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
                    <span>Order code</span>
                    <span>{{ $orderCode ? substr($orderCode, 0, 8) : '—' }}</span>
                </div>
                <div class="success-order-info__row">
                    <span>Items amount</span>
                    <span>{{ $itemsTotal !== null ? number_format($itemsTotal, 2) . ' €' : '—' }}</span>
                </div>
                <div class="success-order-info__row">
                    <span>Delivery</span>
                    <span>{{ $deliveryPrice !== null ? ($deliveryPrice == 0 ? 'Free' : number_format($deliveryPrice, 2) . ' €') : '—' }}</span>
                </div>
                <div class="success-order-info__row">
                    <span>Total paid</span>
                    <span>{{ $grandTotal !== null ? number_format($grandTotal, 2) . ' €' : '—' }}</span>
                </div>
            </div>
            @if($orderCode)
            <p class="success-subtitle" style="font-size:0.85em; margin-top:0.5rem;">
                Save this code — it's how you reference your order.
            </p>
            @endif

            <div class="success-actions">
                <a href="{{ route('home') }}" class="success-btn">Continue shopping</a>
                <a href="{{ route('profile') }}" class="success-btn success-btn--secondary">View my orders</a>
            </div>

        </div>

    </div>
</main>
@endsection

@section('scripts')
@endsection
