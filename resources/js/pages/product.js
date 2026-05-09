export default function initProductPage() {
    const carousel = document.getElementById('productCarousel');
    const thumbs = document.querySelectorAll('.product__thumb');

    if (carousel && window.bootstrap?.Carousel) {
        const bsCarousel = window.bootstrap.Carousel.getOrCreateInstance(carousel);

        thumbs.forEach((thumb, index) => {
            thumb.addEventListener('click', () => {
                bsCarousel.to(index);
            });
        });

        carousel.addEventListener('slid.bs.carousel', event => {
            thumbs.forEach(thumb => thumb.classList.remove('active'));
            const idx = event.to < thumbs.length ? event.to : 0;
            if (thumbs[idx]) {
                thumbs[idx].classList.add('active');
            }
        });
    }

    const qtyInput = document.getElementById('qty-input');
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus = document.getElementById('qty-plus');
    const sizeSelect = document.getElementById('product-size');
    const priceEl = document.getElementById('product-price');
    const addBtn = document.getElementById('add-to-bag');
    const modalEl = document.getElementById('cartToastModal');
    const toastQtyEl = document.getElementById('toast-qty');
    const toastNoteEl = document.getElementById('toast-note');
    const cartAddUrl = addBtn?.dataset?.cartAddUrl;
    let cartModalTimer = null;

    function showCartToast(quantity, note = '') {
        if (!modalEl || !toastQtyEl || !toastNoteEl || !window.bootstrap?.Modal) {
            return;
        }

        toastQtyEl.textContent = String(Math.max(0, Number(quantity) || 0));
        toastNoteEl.textContent = note;

        const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        if (cartModalTimer) {
            clearTimeout(cartModalTimer);
        }

        cartModalTimer = setTimeout(() => modal.hide(), 2500);
    }

    function setQty(value) {
        if (!qtyInput) {
            return;
        }
        const next = Math.max(1, Math.min(99, parseInt(value, 10) || 1));
        qtyInput.value = next;
        if (qtyMinus) {
            qtyMinus.disabled = next <= 1;
        }
    }

    function sanitizeQtyInput(value) {
        return String(value).replace(/[^0-9]/g, '').slice(0, 2);
    }

    if (qtyMinus) {
        qtyMinus.addEventListener('click', () => setQty(Number(qtyInput?.value) - 1));
    }
    if (qtyPlus) {
        qtyPlus.addEventListener('click', () => setQty(Number(qtyInput?.value) + 1));
    }
    if (qtyInput) {
        qtyInput.addEventListener('input', () => {
            // Allow temporary empty value while user is typing.
            qtyInput.value = sanitizeQtyInput(qtyInput.value);
        });
        qtyInput.addEventListener('blur', () => setQty(qtyInput.value));
    }

    function syncVariantSelection() {
        if (!sizeSelect || !addBtn) {
            return;
        }

        const selected = sizeSelect.selectedOptions?.[0];
        addBtn.dataset.variantId = selected?.value || '';

        if (priceEl && selected?.dataset?.price) {
            const price = Number(selected.dataset.price || 0);
            priceEl.textContent = `${price.toFixed(2).replace('.', ',')} \u20AC`;
        }
    }

    if (sizeSelect) {
        sizeSelect.addEventListener('change', syncVariantSelection);
        syncVariantSelection();
    }

    if (addBtn) {
        addBtn.addEventListener('click', async () => {
            const variantId = addBtn.dataset.variantId;
            setQty(qtyInput?.value || 1);
            const quantity = parseInt(qtyInput?.value || '1', 10) || 1;

            if (!variantId) {
                showCartToast(0, 'Variant is not available for this product.');
                return;
            }

            if (!cartAddUrl) {
                showCartToast(0, 'Unable to add item right now.');
                return;
            }

            try {
                const response = await fetch(cartAddUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ variant_id: variantId, quantity }),
                });

                const data = await response.json().catch(() => ({}));
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to add item');
                }

                const addedQuantity = Number(data.added_quantity ?? quantity);
                const wasCapped = Boolean(data.capped) || addedQuantity < quantity;

                showCartToast(
                    addedQuantity,
                    wasCapped
                        ? data.message ||
                              `Only ${addedQuantity} item(s) were added due to stock limit.`
                        : ''
                );
            } catch (error) {
                showCartToast(0, error.message || 'Unable to add item to cart.');
            }
        });
    }

    setQty(1);

    const stars = document.querySelector('.product__rating');
    if (stars) {
        let rating = 0;
        stars.addEventListener('click', event => {
            const rect = stars.getBoundingClientRect();
            rating = Math.ceil(((event.clientX - rect.left) / rect.width) * 5);
            stars.textContent = '\u2605'.repeat(rating) + '\u2606'.repeat(5 - rating);
        });
    }
}
