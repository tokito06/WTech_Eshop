export default function initProfilePage() {
    const editBtn = document.getElementById('btn-edit');
    const profileForm = document.getElementById('profile-form');
    if (!editBtn || !profileForm) {
        return;
    }

    const fields = profileForm.querySelectorAll('.info-field');
    let editing = false;

    function showToast(message) {
        const toastMsg = document.getElementById('toast-msg');
        const toastEl = document.getElementById('profileToast');
        if (!toastMsg || !toastEl || !window.bootstrap?.Toast) {
            return;
        }
        toastMsg.textContent = message;
        window.bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 2500 }).show();
    }

    editBtn.addEventListener('click', () => {
        editing = !editing;
        fields.forEach(field => {
            if (field.name === 'name' || field.name === 'email' || field.name === 'phone') {
                if (editing) {
                    field.removeAttribute('readonly');
                } else {
                    field.setAttribute('readonly', '');
                }
            }
        });

        if (editing) {
            document.getElementById('field-name')?.focus();
        } else {
            profileForm.submit();
        }

        editBtn.textContent = editing ? 'Save changes' : 'Edit profile';
        editBtn.classList.toggle('btn-edit--active', editing);
    });

    if (profileForm.dataset.profileStatus === 'profile-updated') {
        showToast('Profile saved!');
    }
}
