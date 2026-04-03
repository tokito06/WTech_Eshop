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

        <div class="cart-title">
            <span class="material-symbols-outlined">shopping_cart</span>
            <h1>Cart</h1>
        </div>

        <div class="row g-4 align-items-start">

            <!-- Goods list -->
            <section class="col-12 col-lg-8">

                <div class="cart-empty" id="cart-empty">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <p>Your cart is empty</p>
                </div>

                <div id="cart-items-list">
                    <h2 class="cart-items__title">Items</h2>

                    <div class="cart-item" data-price="100.99">
                        <label class="cart-item__checkbox">
                            <input type="checkbox" aria-label="Select item">
                            <span class="cart-item__checkbox-box"></span>
                        </label>
                        <div class="cart-item__image">
                            <img class="img__container" src="{{ asset('images/image_1.jpg') }}" alt="Cart product image">
                        </div>
                        <div class="cart-item__info">
                            <h3>Super View Glasses</h3>
                            <p>UV-protective lenses with a lightweight frame. Wide field of view for everyday outdoor wear.</p>
                            <div class="cart-item__meta">
                                <span class="cart-item__size-badge">M</span>
                                <span class="cart-item__price">100.99 €</span>
                            </div>
                        </div>
                        <div class="cart-item__controls">
                            <button class="cart-item__ctrl-btn" data-action="inc" aria-label="Increase">+</button>
                            <span class="cart-item__count">4</span>
                            <button class="cart-item__ctrl-btn" data-action="dec" aria-label="Decrease">−</button>
                        </div>
                        <button class="cart-item__delete" aria-label="Remove item" title="Remove">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>

                    <div class="cart-item" data-price="59.00">
                        <label class="cart-item__checkbox">
                            <input type="checkbox" aria-label="Select item">
                            <span class="cart-item__checkbox-box"></span>
                        </label>
                        <div class="cart-item__image">
                            <img class="img__container" src="{{ asset('images/image_2.jpg') }}" alt="Cart product image">
                        </div>
                        <div class="cart-item__info">
                            <h3>Red Jacket</h3>
                            <p>Bold red jacket with a modern slim cut. Water-resistant fabric, perfect for spring and autumn.</p>
                            <div class="cart-item__meta">
                                <span class="cart-item__size-badge">XL</span>
                                <span class="cart-item__price">59.00 €</span>
                            </div>
                        </div>
                        <div class="cart-item__controls">
                            <button class="cart-item__ctrl-btn" data-action="inc" aria-label="Increase">+</button>
                            <span class="cart-item__count">1</span>
                            <button class="cart-item__ctrl-btn" data-action="dec" aria-label="Decrease">−</button>
                        </div>
                        <button class="cart-item__delete" aria-label="Remove item" title="Remove">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Cart summary -->
            <section class="col-12 col-lg-4 cart-sticky">
                <div class="cart-summary">
                    <h2 class="cart-summary__title">Cart summary</h2>
                    <div class="cart-summary__amounts">
                        <div class="cart-summary__amount">
                            <span>Items amount</span>
                            <span id="summary-items">—</span>
                        </div>
                        <div class="cart-summary__amount">
                            <span>Delivery</span>
                            <small>from 0 €</small>
                        </div>
                        <div class="cart-summary__amount">
                            <span>Total</span>
                            <strong class="cart-summary__total" id="summary-total">—</strong>
                        </div>
                    </div>
                    <a href="{{ route('delivery') }}" class="cart-summary__btn">Go to checkout</a>
                </div>
            </section>

        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    function updateSummary() {
        const items = document.querySelectorAll('.cart-item');
        let total = 0;
        items.forEach(item => {
            const price = parseFloat(item.dataset.price) || 0;
            const qty   = parseInt(item.querySelector('.cart-item__count').textContent) || 0;
            total += price * qty;
        });
        if (items.length === 0) {
            document.getElementById('summary-items').textContent = '0.00 €';
            document.getElementById('summary-total').textContent = '0.00 €';
            document.getElementById('cart-empty').style.display = 'flex';
        } else {
            document.getElementById('summary-items').textContent = total.toFixed(2) + ' €';
            document.getElementById('summary-total').textContent = total.toFixed(2) + ' €';
            document.getElementById('cart-empty').style.display = 'none';
        }
    }

    document.getElementById('cart-items-list').addEventListener('click', e => {
        const ctrlBtn = e.target.closest('.cart-item__ctrl-btn');
        if (ctrlBtn) {
            const item    = ctrlBtn.closest('.cart-item');
            const countEl = item.querySelector('.cart-item__count');
            let count = parseInt(countEl.textContent) || 1;
            if (ctrlBtn.dataset.action === 'inc') count++;
            if (ctrlBtn.dataset.action === 'dec') count = Math.max(1, count - 1);
            countEl.textContent = count;
            updateSummary();
            return;
        }
        const delBtn = e.target.closest('.cart-item__delete');
        if (delBtn) {
            const item = delBtn.closest('.cart-item');
            item.style.transition = 'opacity 0.25s, transform 0.25s';
            item.style.opacity    = '0';
            item.style.transform  = 'translateX(30px)';
            setTimeout(() => { item.remove(); updateSummary(); }, 260);
        }
    });

    updateSummary();
</script>
@endsection
