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

                <div id="cart-items-list">
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
@endsection

@section('scripts')
<script>
    const cartState = { items: [] };
    const cartList = document.getElementById('cart-items-list');
    const emptyState = document.getElementById('cart-empty');
    const cartStockToast = document.getElementById('cartStockToast');
    const cartStockToastMessage = document.getElementById('cart-stock-toast-message');
    const cartUpdateUrlTemplate = '{{ route('cart.update', ['item' => '__ITEM_ID__']) }}';
    const cartRemoveUrlTemplate = '{{ route('cart.remove', ['item' => '__ITEM_ID__']) }}';
    const initialCartItems = @json($cartItems->values());

    function formatPrice(value) {
        return Number(value || 0).toFixed(2) + ' €';
    }

    function hydrateCartItems(items) {
        return (items || []).map(item => ({
            ...item,
            id: String(item.id),
            quantity: Number(item.quantity) || 1,
            amount: Number(item.amount) || 0,
        }));
    }

    function showStockToast(message) {
        if (!message || !cartStockToast || !cartStockToastMessage) {
            return;
        }

        cartStockToastMessage.textContent = message;
        bootstrap.Toast.getOrCreateInstance(cartStockToast, { delay: 2500 }).show();
    }

    function getQuantityLimit(item) {
        const inventory = Number(item?.variant?.inventory);
        if (Number.isFinite(inventory) && inventory > 0) {
            return Math.min(99, inventory);
        }

        return 99;
    }

    function sanitizeQuantityInput(value) {
        return String(value).replace(/[^0-9]/g, '');
    }

    function normalizeQuantity(value, item) {
        const parsed = Number.parseInt(value, 10);
        const limit = getQuantityLimit(item);

        if (!Number.isFinite(parsed)) {
            return 1;
        }

        return Math.max(1, Math.min(limit, parsed));
    }

    function updateSummary() {
        const total = cartState.items.reduce((sum, item) => sum + (Number(item.amount) * Number(item.quantity)), 0);
        document.getElementById('summary-items').textContent = formatPrice(total);
        document.getElementById('summary-total').textContent = formatPrice(total);
        emptyState.style.display = cartState.items.length ? 'none' : 'flex';
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) checkoutBtn.classList.toggle('cart-summary__btn--disabled', !cartState.items.length);
        const title = cartList.querySelector('.cart-items__title');
        if (!cartState.items.length && title) {
            title.remove();
        }
    }

    cartState.items = hydrateCartItems(initialCartItems);
    updateSummary();

    async function syncQuantity(itemId, quantity) {
        const response = await fetch(cartUpdateUrlTemplate.replace('__ITEM_ID__', encodeURIComponent(itemId)), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({ quantity }),
        });

        if (!response.ok) {
            const payload = await response.json().catch(() => ({}));
            throw new Error(payload.message || 'Failed to update item quantity');
        }

        return response.json().catch(() => ({}));
    }

    async function removeItem(itemId) {
        const response = await fetch(cartRemoveUrlTemplate.replace('__ITEM_ID__', encodeURIComponent(itemId)), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            const payload = await response.json().catch(() => ({}));
            throw new Error(payload.message || 'Failed to remove item');
        }
    }

    async function updateCartQuantity(card, item, nextQuantity) {
        const requestedQuantity = Number.parseInt(nextQuantity, 10);
        const quantityLimit = getQuantityLimit(item);
        const normalized = normalizeQuantity(nextQuantity, item);
        const quantityInput = card.querySelector('.cart-item__count-input');
        const previousQuantity = item.quantity;

        if (Number.isFinite(requestedQuantity) && requestedQuantity > quantityLimit) {
            showStockToast(`Only ${quantityLimit} item(s) are available for this size.`);
        }

        if (quantityInput) {
            quantityInput.value = normalized;
        }

        if (normalized === previousQuantity) {
            return;
        }

        try {
            const payload = await syncQuantity(item.id, normalized);
            const appliedQuantity = Number(payload.quantity ?? normalized);

            item.quantity = appliedQuantity;
            if (quantityInput) {
                quantityInput.value = appliedQuantity;
            }

            if (Boolean(payload.capped) || appliedQuantity < normalized) {
                showStockToast(payload.message || `Only ${appliedQuantity} item(s) are available for this size.`);
            }

            updateSummary();
        } catch (error) {
            if (quantityInput) {
                quantityInput.value = previousQuantity;
            }
            showStockToast(error.message || 'Unable to update item quantity.');
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

        const currentQuantity = Number(item.quantity) || 1;

        const ctrlBtn = e.target.closest('.cart-item__ctrl-btn');
        if (ctrlBtn) {
            const limit = getQuantityLimit(item);

            if (ctrlBtn.dataset.action === 'inc' && currentQuantity >= limit) {
                showStockToast(`Only ${limit} item(s) are available for this size.`);
                return;
            }

            const nextQuantity = ctrlBtn.dataset.action === 'inc'
                ? currentQuantity + 1
                : Math.max(1, currentQuantity - 1);

            await updateCartQuantity(card, item, nextQuantity);
            return;
        }

        if (e.target.closest('.cart-item__delete')) {
            try {
                await removeItem(itemId);
                cartState.items = cartState.items.filter(i => i.id !== itemId);
                card.remove();
                updateSummary();
            } catch (error) {
                alert(error.message);
            }
        }
    });

    cartList.addEventListener('change', async e => {
        const quantityInput = e.target.closest('.cart-item__count-input');
        if (!quantityInput) {
            return;
        }

        const card = quantityInput.closest('.cart-item');
        const item = cartState.items.find(entry => entry.id === card?.dataset.id);

        if (!card || !item) {
            return;
        }

        await updateCartQuantity(card, item, quantityInput.value);
    });

    cartList.addEventListener('input', e => {
        const quantityInput = e.target.closest('.cart-item__count-input');
        if (!quantityInput) {
            return;
        }

        const card = quantityInput.closest('.cart-item');

        if (!card) {
            return;
        }

        const item = cartState.items.find(entry => entry.id === card.dataset.id);
        const maxQuantityLength = String(getQuantityLimit(item)).length;
        quantityInput.value = sanitizeQuantityInput(quantityInput.value).slice(0, maxQuantityLength);
    });

    cartList.addEventListener('keydown', e => {
        const quantityInput = e.target.closest('.cart-item__count-input');
        if (!quantityInput || e.key !== 'Enter') {
            return;
        }

        e.preventDefault();
        quantityInput.blur();
    });

</script>
@endsection
