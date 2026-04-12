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

                <div id="cart-items-list"></div>
            </section>

            <!-- Cart summary -->
            <section class="col-12 col-lg-4 cart-sticky">
                <div class="cart-summary">
                    <h2 class="cart-summary__title">Cart summary</h2>
                    <div class="cart-summary__amounts">
                        <div class="cart-summary__amount">
                            <span>Items amount</span>
                            <span id="summary-items">0.00 €</span>
                        </div>
                        <div class="cart-summary__amount">
                            <span>Delivery</span>
                            <small>from 0 €</small>
                        </div>
                        <div class="cart-summary__amount">
                            <span>Total</span>
                            <strong class="cart-summary__total" id="summary-total">0.00 €</strong>
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
    const cartState = { items: [] };
    const cartList = document.getElementById('cart-items-list');
    const emptyState = document.getElementById('cart-empty');

    function formatPrice(value) {
        return Number(value || 0).toFixed(2) + ' €';
    }

    function updateSummary() {
        const total = cartState.items.reduce((sum, item) => sum + (Number(item.amount) * Number(item.quantity)), 0);
        document.getElementById('summary-items').textContent = formatPrice(total);
        document.getElementById('summary-total').textContent = formatPrice(total);
        emptyState.style.display = cartState.items.length ? 'none' : 'flex';
    }

    function renderCart() {
        if (!cartState.items.length) {
            cartList.innerHTML = '';
            updateSummary();
            return;
        }

        const cards = cartState.items.map(item => {
            const product = item.variant?.product;
            const image = product?.images?.[0]?.url || '{{ asset('images/image_1.jpg') }}';
            const size = item.variant?.symbol || 'N/A';
            const price = Number(item.amount || 0);

            return `
                <div class="cart-item" data-id="${item.id}">
                    <label class="cart-item__checkbox">
                        <input type="checkbox" aria-label="Select item">
                        <span class="cart-item__checkbox-box"></span>
                    </label>
                    <div class="cart-item__image">
                        <img class="img__container" src="${image}" alt="Cart product image">
                    </div>
                    <div class="cart-item__info">
                        <h3>${product?.name ?? 'Product'}</h3>
                        <p>${product?.description ?? ''}</p>
                        <div class="cart-item__meta">
                            <span class="cart-item__size-badge">${size}</span>
                            <span class="cart-item__price">${formatPrice(price)}</span>
                        </div>
                    </div>
                    <div class="cart-item__controls">
                        <button class="cart-item__ctrl-btn" data-action="inc" aria-label="Increase">+</button>
                        <span class="cart-item__count">${item.quantity}</span>
                        <button class="cart-item__ctrl-btn" data-action="dec" aria-label="Decrease">−</button>
                    </div>
                    <button class="cart-item__delete" aria-label="Remove item" title="Remove">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            `;
        }).join('');

        cartList.innerHTML = `<h2 class="cart-items__title">Items</h2>${cards}`;
        updateSummary();
    }

    async function loadCart() {
        const response = await fetch('{{ route('cart.get') }}');
        const data = await response.json();
        cartState.items = data.items || [];
        renderCart();
    }

    async function syncQuantity(itemId, quantity) {
        const response = await fetch(`/api/cart/item/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ quantity }),
        });

        if (!response.ok) {
            throw new Error('Failed to update item quantity');
        }
    }

    async function removeItem(itemId) {
        const response = await fetch(`/api/cart/item/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        if (!response.ok) {
            throw new Error('Failed to remove item');
        }
    }

    cartList.addEventListener('click', async e => {
        const card = e.target.closest('.cart-item');
        if (!card) {
            return;
        }

        const itemId = card.dataset.id;
        const item = cartState.items.find(i => i.id === itemId);
        if (!item) {
            return;
        }

        const ctrlBtn = e.target.closest('.cart-item__ctrl-btn');
        if (ctrlBtn) {
            const nextQuantity = ctrlBtn.dataset.action === 'inc'
                ? item.quantity + 1
                : Math.max(1, item.quantity - 1);

            try {
                await syncQuantity(itemId, nextQuantity);
                item.quantity = nextQuantity;
                renderCart();
            } catch (error) {
                alert(error.message);
            }
            return;
        }

        if (e.target.closest('.cart-item__delete')) {
            try {
                await removeItem(itemId);
                cartState.items = cartState.items.filter(i => i.id !== itemId);
                renderCart();
            } catch (error) {
                alert(error.message);
            }
        }
    });

    loadCart().catch(() => {
        emptyState.style.display = 'flex';
    });
</script>
@endsection
