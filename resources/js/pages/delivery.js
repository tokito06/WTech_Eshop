export default function initDeliveryPage() {
    const deliveryMethodField = document.getElementById('delivery-method-id');
    const preselected = document.querySelector('.service-card input[type="radio"]:checked');
    const summaryDelivery = document.getElementById('summary-delivery');
    const summaryTotal = document.getElementById('summary-total');
    const summaryItems = document.getElementById('summary-items');
    const itemsTotalValue = Number(summaryItems?.dataset.itemsTotal || 0) || 0;
    const checkoutEmail = document.getElementById('email');
    const checkoutLoginLink = document.getElementById('checkout-login-link');
    const checkoutRegisterLink = document.getElementById('checkout-register-link');

    function formatPrice(value) {
        return Number(value || 0).toFixed(2) + ' \u20AC';
    }

    function updateSummary(deliveryPrice) {
        if (summaryDelivery) {
            summaryDelivery.textContent =
                deliveryPrice === 0 ? 'Free' : formatPrice(deliveryPrice);
        }
        if (summaryTotal) {
            summaryTotal.textContent = formatPrice(itemsTotalValue + deliveryPrice);
        }
        if (summaryItems) {
            summaryItems.textContent = formatPrice(itemsTotalValue);
        }
    }

    function updateAuthLinks() {
        if (!checkoutEmail || !checkoutLoginLink || !checkoutRegisterLink) {
            return;
        }

        const emailValue = checkoutEmail.value.trim();
        const suffix = emailValue ? `?email=${encodeURIComponent(emailValue)}` : '';

        checkoutLoginLink.href = `${checkoutLoginLink.dataset.baseHref}${suffix}`;
        checkoutRegisterLink.href = `${checkoutRegisterLink.dataset.baseHref}${suffix}`;
    }

    if (preselected) {
        preselected.closest('.service-card')?.classList.add('service-card--selected');
        if (deliveryMethodField) {
            deliveryMethodField.value = preselected.value;
        }
        const price = Number(preselected.dataset.price || 0);
        updateSummary(price);
    }

    if (checkoutEmail && checkoutLoginLink && checkoutRegisterLink) {
        updateAuthLinks();
        checkoutEmail.addEventListener('input', updateAuthLinks);
    }

    document.querySelectorAll('.service-card input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document
                .querySelectorAll('.service-card')
                .forEach(card => card.classList.remove('service-card--selected'));
            radio.closest('.service-card')?.classList.add('service-card--selected');
            document
                .getElementById('service-error')
                ?.classList.remove('delivery-services__error--visible');
            if (deliveryMethodField) {
                deliveryMethodField.value = radio.value;
            }
            const price = Number(radio.dataset.price || 0);
            updateSummary(price);
        });
    });

    const paymentBtn = document.getElementById('payment-btn');
    if (paymentBtn) {
        paymentBtn.addEventListener('click', () => {
            const form = document.getElementById('delivery-form');
            if (!form) {
                return;
            }
            const inputs = form.querySelectorAll('input[required]');
            const serviceChosen = document.querySelector(
                '.service-card input[type="radio"]:checked'
            );
            let valid = true;

            if (serviceChosen && deliveryMethodField) {
                deliveryMethodField.value = serviceChosen.value;
            }

            if (!serviceChosen) {
                document
                    .getElementById('service-error')
                    ?.classList.add('delivery-services__error--visible');
                document
                    .getElementById('delivery-services')
                    ?.classList.add('delivery-services--error');
                valid = false;
            } else {
                document
                    .getElementById('delivery-services')
                    ?.classList.remove('delivery-services--error');
            }

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('delivery-input--error');
                    valid = false;
                } else {
                    input.classList.remove('delivery-input--error');
                }
            });

            if (valid) {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                form.submit();
            }
        });
    }

    const deliveryForm = document.getElementById('delivery-form');
    if (deliveryForm) {
        deliveryForm.addEventListener('input', event => {
            if (event.target.value.trim()) {
                event.target.classList.remove('delivery-input--error');
            }
        });
    }
}
