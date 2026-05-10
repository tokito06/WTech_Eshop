export default function initPaymentPage() {
    const cardNumber = document.getElementById('card-number');
    if (cardNumber) {
        cardNumber.addEventListener('input', () => {
            let value = cardNumber.value.replace(/\D/g, '').substring(0, 16);
            cardNumber.value = value.replace(/(.{4})/g, '$1 ').trim();
        });
    }

    const cardExpiry = document.getElementById('card-expiry');
    if (cardExpiry) {
        cardExpiry.addEventListener('input', () => {
            let value = cardExpiry.value.replace(/\D/g, '').substring(0, 4);
            if (value.length >= 3) {
                value = value.substring(0, 2) + ' / ' + value.substring(2);
            }
            cardExpiry.value = value;
        });
    }

    const cardCvv = document.getElementById('card-cvv');
    if (cardCvv) {
        cardCvv.addEventListener('input', () => {
            cardCvv.value = cardCvv.value.replace(/\D/g, '').substring(0, 4);
        });
    }
}
