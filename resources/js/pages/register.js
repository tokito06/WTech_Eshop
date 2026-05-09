export default function initRegisterPage() {
    const form = document.getElementById('reg-form');
    if (!form) {
        return;
    }

    form.addEventListener('submit', event => {
        const fields = [
            { id: 'inp-name', check: value => value.trim().length > 0 },
            { id: 'inp-surname', check: value => value.trim().length > 0 },
            { id: 'inp-email', check: value => /\S+@\S+\.\S+/.test(value) },
            { id: 'inp-password', check: value => value.length >= 8 },
            { id: 'inp-type', check: value => value !== '' },
        ];

        let valid = true;

        fields.forEach(({ id, check }) => {
            const input = document.getElementById(id);
            if (!input) {
                return;
            }
            const isOk = check(input.value);
            input.style.boxShadow = isOk ? '' : '0 0 0 3px rgba(224, 85, 85, 0.35)';
            if (!isOk) {
                valid = false;
            }
        });

        if (!valid) {
            event.preventDefault();
        }
    });
}
