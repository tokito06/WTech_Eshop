export default function initCartPage() {
    const cartList = document.getElementById('cart-items-list');
    if (!cartList) {
        return;
    }

    const emptyState = document.getElementById('cart-empty');
    const cartStockToast = document.getElementById('cartStockToast');
    const cartStockToastMessage = document.getElementById('cart-stock-toast-message');
    const cartUpdateUrlTemplate = cartList.dataset.updateUrlTemplate;
    const cartRemoveUrlTemplate = cartList.dataset.removeUrlTemplate;
    const initialDataEl = document.getElementById('cart-items-data');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    let initialCartItems = [];
    if (initialDataEl?.textContent) {
        try {
            initialCartItems = JSON.parse(initialDataEl.textContent);
        } catch (error) {
            console.error(error);
        }
    }

    const cartState = { items: [] };

    function formatPrice(value) {
        return Number(value || 0).toFixed(2) + ' \u20AC';
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
        if (!window.bootstrap?.Toast) {
            return;
        }

        cartStockToastMessage.textContent = message;
        window.bootstrap.Toast.getOrCreateInstance(cartStockToast, { delay: 2500 }).show();
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
        const total = cartState.items.reduce(
            (sum, item) => sum + Number(item.amount) * Number(item.quantity),
            0
        );
        const summaryItems = document.getElementById('summary-items');
        const summaryTotal = document.getElementById('summary-total');
        if (summaryItems) {
            summaryItems.textContent = formatPrice(total);
        }
        if (summaryTotal) {
            summaryTotal.textContent = formatPrice(total);
        }
        if (emptyState) {
            emptyState.style.display = cartState.items.length ? 'none' : 'flex';
        }
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.classList.toggle('cart-summary__btn--disabled', !cartState.items.length);
        }
        const title = cartList.querySelector('.cart-items__title');
        if (!cartState.items.length && title) {
            title.remove();
        }
    }

    cartState.items = hydrateCartItems(initialCartItems);
    updateSummary();

    async function syncQuantity(itemId, quantity) {
        if (!cartUpdateUrlTemplate) {
            throw new Error('Update URL is missing.');
        }
        const response = await fetch(
            cartUpdateUrlTemplate.replace('__ITEM_ID__', encodeURIComponent(itemId)),
            {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ quantity }),
            }
        );

        if (!response.ok) {
            const payload = await response.json().catch(() => ({}));
            throw new Error(payload.message || 'Failed to update item quantity');
        }

        return response.json().catch(() => ({}));
    }

    async function removeItem(itemId) {
        if (!cartRemoveUrlTemplate) {
            throw new Error('Remove URL is missing.');
        }
        const response = await fetch(
            cartRemoveUrlTemplate.replace('__ITEM_ID__', encodeURIComponent(itemId)),
            {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                credentials: 'same-origin',
            }
        );

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
                showStockToast(
                    payload.message || `Only ${appliedQuantity} item(s) are available for this size.`
                );
            }

            updateSummary();
        } catch (error) {
            if (quantityInput) {
                quantityInput.value = previousQuantity;
            }
            showStockToast(error.message || 'Unable to update item quantity.');
        }
    }

    cartList.addEventListener('click', async event => {
        const card = event.target.closest('.cart-item');
        if (!card) {
            return;
        }

        const itemId = card.dataset.id;
        const item = cartState.items.find(entry => entry.id === itemId);
        if (!item) {
            return;
        }

        const currentQuantity = Number(item.quantity) || 1;

        const ctrlBtn = event.target.closest('.cart-item__ctrl-btn');
        if (ctrlBtn) {
            const limit = getQuantityLimit(item);

            if (ctrlBtn.dataset.action === 'inc' && currentQuantity >= limit) {
                showStockToast(`Only ${limit} item(s) are available for this size.`);
                return;
            }

            const nextQuantity =
                ctrlBtn.dataset.action === 'inc'
                    ? currentQuantity + 1
                    : Math.max(1, currentQuantity - 1);

            await updateCartQuantity(card, item, nextQuantity);
            return;
        }

        if (event.target.closest('.cart-item__delete')) {
            try {
                await removeItem(itemId);
                cartState.items = cartState.items.filter(entry => entry.id !== itemId);
                card.remove();
                updateSummary();
            } catch (error) {
                alert(error.message);
            }
        }
    });

    cartList.addEventListener('change', async event => {
        const quantityInput = event.target.closest('.cart-item__count-input');
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

    cartList.addEventListener('input', event => {
        const quantityInput = event.target.closest('.cart-item__count-input');
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

    cartList.addEventListener('keydown', event => {
        const quantityInput = event.target.closest('.cart-item__count-input');
        if (!quantityInput || event.key !== 'Enter') {
            return;
        }

        event.preventDefault();
        quantityInput.blur();
    });
}
