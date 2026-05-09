export default function initAdminEditProductPage() {
    const photoDrop = document.getElementById('photo-drop');
    const photoInput = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');
    const photoLabel = document.getElementById('photo-label');

    if (!photoDrop || !photoInput || !photoPreview || photoInput.multiple) {
        return;
    }

    photoDrop.addEventListener('click', () => photoInput.click());
    photoDrop.addEventListener('dragover', event => {
        event.preventDefault();
        photoDrop.classList.add('drag-over');
    });
    photoDrop.addEventListener('dragleave', () => photoDrop.classList.remove('drag-over'));
    photoDrop.addEventListener('drop', event => {
        event.preventDefault();
        photoDrop.classList.remove('drag-over');
        const file = event.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            photoInput.files = event.dataTransfer.files;
            showPreview(file);
        }
    });
    photoInput.addEventListener('change', () => {
        if (photoInput.files[0]) {
            showPreview(photoInput.files[0]);
        }
    });

    function showPreview(file) {
        photoPreview.src = URL.createObjectURL(file);
        photoPreview.style.display = 'block';
        if (photoLabel) {
            photoLabel.style.display = 'none';
        }
    }
}
