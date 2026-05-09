export default function initAdminAddProductPage() {
    const photoDrop = document.getElementById('photo-drop');
    const photoInput = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');
    const photoLabel = document.getElementById('photo-label');
    const photoThumbs = document.getElementById('photo-thumbs');
    const uploadNote = document.getElementById('photo-upload-note');

    const previewPrev = document.getElementById('preview-prev');
    const previewNext = document.getElementById('preview-next');
    const previewImage = document.getElementById('preview-image');
    const previewPlaceholder = document.getElementById('preview-placeholder');
    const previewName = document.getElementById('preview-name');
    const previewDescription = document.getElementById('preview-description');
    const previewCategory = document.getElementById('preview-category');
    const previewSex = document.getElementById('preview-sex');
    const previewBrand = document.getElementById('preview-brand');
    const previewPrice = document.getElementById('preview-price');
    const previewSizes = document.getElementById('preview-sizes');

    const nameInput = document.querySelector('input[name="name"]');
    const descriptionInput = document.querySelector('textarea[name="description"]');
    const categorySelect = document.querySelector('select[name="category_id"]');
    const sexSelect = document.querySelector('select[name="sex"]');
    const brandSelect = document.querySelector('select[name="brand_id"]');
    const priceInput = document.querySelector('input[name="price"]');

    if (!photoDrop || !photoInput || !photoThumbs) {
        return;
    }

    let selectedFiles = [];
    let thumbUrls = [];
    let currentIndex = -1;

    function canSyncFiles() {
        return typeof DataTransfer !== 'undefined';
    }

    function clearThumbs() {
        photoThumbs.innerHTML = '';
    }

    function createEmptyThumb() {
        const empty = document.createElement('div');
        empty.className = 'add-product-thumb add-product-thumb--empty';
        const label = document.createElement('span');
        label.className = 'add-product-thumb__label';
        label.textContent = 'No photos yet';
        empty.appendChild(label);
        photoThumbs.appendChild(empty);
    }

    function syncInputFiles() {
        if (!canSyncFiles()) {
            return;
        }
        const data = new DataTransfer();
        selectedFiles.forEach(file => data.items.add(file));
        photoInput.files = data.files;
    }

    function setUploadNote(message) {
        if (!uploadNote) {
            return;
        }
        if (message) {
            uploadNote.textContent = message;
            uploadNote.hidden = false;
            return;
        }
        uploadNote.textContent = '';
        uploadNote.hidden = true;
    }

    function syncThumbs() {
        thumbUrls.forEach(url => URL.revokeObjectURL(url));
        thumbUrls = selectedFiles.map(file => URL.createObjectURL(file));

        clearThumbs();

        if (selectedFiles.length === 0) {
            createEmptyThumb();
            photoPreview?.removeAttribute('src');
            if (photoPreview) {
                photoPreview.style.display = 'none';
            }
            if (photoLabel) {
                photoLabel.style.display = 'block';
            }
            if (previewImage) {
                previewImage.hidden = true;
                previewImage.removeAttribute('src');
            }
            if (previewPlaceholder) {
                previewPlaceholder.hidden = false;
            }
            currentIndex = -1;
            updateNavState();
            return;
        }

        thumbUrls.forEach((url, index) => {
            const thumb = document.createElement('div');
            thumb.className = `add-product-thumb${index === 0 ? ' active' : ''}`;
            thumb.setAttribute('role', 'button');
            thumb.tabIndex = 0;
            thumb.dataset.index = String(index);

            const thumbImg = document.createElement('img');
            thumbImg.src = url;
            thumbImg.alt = `Uploaded photo ${index + 1}`;
            thumb.appendChild(thumbImg);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'add-product-thumb__remove';
            removeBtn.setAttribute('aria-label', 'Remove photo');
            removeBtn.dataset.index = String(index);
            removeBtn.innerHTML = '<span class="material-symbols-outlined">close</span>';
            thumb.appendChild(removeBtn);

            photoThumbs.appendChild(thumb);
        });

        setPreviewByIndex(0);
    }

    function removeFileAt(index) {
        if (index < 0 || index >= selectedFiles.length) {
            return;
        }
        selectedFiles.splice(index, 1);
        syncInputFiles();
        syncThumbs();

        if (selectedFiles.length === 0) {
            currentIndex = -1;
            return;
        }

        const nextIndex = Math.min(index, selectedFiles.length - 1);
        setPreviewByIndex(nextIndex);
    }

    function mergeFiles(fileList, options = {}) {
        const { fromInput = false } = options;

        if (!canSyncFiles() && !fromInput) {
            setUploadNote('Drag and drop is not supported in this browser. Use the file picker.');
            return;
        }

        const incoming = Array.from(fileList || []);
        const images = incoming.filter(file => file && file.type && file.type.startsWith('image/'));
        const rejectedCount = incoming.length - images.length;

        if (rejectedCount > 0) {
            setUploadNote(
                `${rejectedCount} file${rejectedCount > 1 ? 's were' : ' was'} skipped (images only).`
            );
        } else {
            setUploadNote('');
        }

        const existingKeys = new Set(
            selectedFiles.map(file => `${file.name}-${file.size}-${file.lastModified}`)
        );

        images.forEach(file => {
            const key = `${file.name}-${file.size}-${file.lastModified}`;
            if (!existingKeys.has(key)) {
                selectedFiles.push(file);
                existingKeys.add(key);
            }
        });

        if (images.length) {
            syncInputFiles();
            syncThumbs();
        }

        if (canSyncFiles()) {
            photoInput.value = '';
        }
    }

    function setPreview(url) {
        if (!url) {
            return;
        }
        if (photoLabel) {
            photoLabel.style.display = 'block';
        }
        if (photoPreview) {
            photoPreview.style.display = 'none';
        }

        if (previewImage) {
            previewImage.src = url;
            previewImage.hidden = false;
        }
        if (previewPlaceholder) {
            previewPlaceholder.hidden = true;
        }
    }

    function setPreviewByIndex(index) {
        if (!thumbUrls.length) {
            currentIndex = -1;
            return;
        }

        currentIndex = Math.max(0, Math.min(index, thumbUrls.length - 1));
        setPreview(thumbUrls[currentIndex]);
        updateNavState();
        updateActiveThumb();
    }

    function updateNavState() {
        const disablePrev = thumbUrls.length <= 1 || currentIndex <= 0;
        const disableNext = thumbUrls.length <= 1 || currentIndex >= thumbUrls.length - 1;
        previewPrev?.toggleAttribute('disabled', disablePrev);
        previewNext?.toggleAttribute('disabled', disableNext);
    }

    function updateActiveThumb() {
        const active = photoThumbs.querySelector('.add-product-thumb.active');
        if (active) {
            active.classList.remove('active');
        }
        const thumbs = photoThumbs.querySelectorAll('.add-product-thumb');
        const target = thumbs[currentIndex];
        if (target) {
            target.classList.add('active');
        }
    }

    function syncPreviewText(element, value) {
        if (!element) {
            return;
        }
        element.textContent = value && value.trim() ? value.trim() : '\u2014';
    }

    function syncSelectText(element, select) {
        if (!element || !select) {
            return;
        }
        const option = select.selectedOptions[0];
        const text = option && option.value ? option.textContent.trim() : '';
        element.textContent = text || '\u2014';
    }

    function syncSizes() {
        const inputs = document.querySelectorAll('input[name^="inventory["]');
        const selected = [];
        inputs.forEach(input => {
            const size = input.name.match(/inventory\[(.*)\]/)?.[1];
            const value = parseInt(input.value, 10) || 0;
            if (size && value > 0) {
                selected.push(size);
            }
        });
        if (previewSizes) {
            previewSizes.textContent = selected.length ? selected.join(', ') : '\u2014';
        }
    }

    function syncPreview() {
        syncPreviewText(previewName, nameInput?.value || '');
        syncPreviewText(previewDescription, descriptionInput?.value || '');
        syncSelectText(previewCategory, categorySelect);
        syncSelectText(previewSex, sexSelect);
        syncSelectText(previewBrand, brandSelect);
        const price = priceInput?.value ? `${Number(priceInput.value).toFixed(2)} \u20AC` : '';
        if (previewPrice) {
            previewPrice.textContent = price || '\u2014';
        }
        syncSizes();
    }

    [nameInput, descriptionInput, categorySelect, sexSelect, brandSelect, priceInput].forEach(el => {
        el?.addEventListener('input', syncPreview);
        el?.addEventListener('change', syncPreview);
    });

    document.querySelectorAll('input[name^="inventory["]').forEach(input => {
        input.addEventListener('input', syncSizes);
    });

    photoDrop.addEventListener('click', () => photoInput.click());
    photoDrop.addEventListener('dragover', event => {
        event.preventDefault();
        photoDrop.classList.add('drag-over');
    });
    photoDrop.addEventListener('dragleave', () => photoDrop.classList.remove('drag-over'));
    photoDrop.addEventListener('drop', event => {
        event.preventDefault();
        photoDrop.classList.remove('drag-over');
        const files = event.dataTransfer.files;
        if (files && files.length) {
            mergeFiles(files, { fromInput: false });
        }
    });
    photoInput.addEventListener('change', () => mergeFiles(photoInput.files, { fromInput: true }));

    previewPrev?.addEventListener('click', () => setPreviewByIndex(currentIndex - 1));
    previewNext?.addEventListener('click', () => setPreviewByIndex(currentIndex + 1));

    photoThumbs.addEventListener('click', event => {
        const removeBtn = event.target.closest('.add-product-thumb__remove');
        if (removeBtn) {
            event.stopPropagation();
            const index = Number(removeBtn.dataset.index);
            if (!Number.isNaN(index)) {
                removeFileAt(index);
            }
            return;
        }

        const thumb = event.target.closest('.add-product-thumb');
        if (thumb) {
            const index = Number(thumb.dataset.index);
            if (!Number.isNaN(index)) {
                setPreviewByIndex(index);
            }
        }
    });

    photoThumbs.addEventListener('keydown', event => {
        if (event.key !== 'Enter' && event.key !== ' ') {
            return;
        }
        const thumb = event.target.closest('.add-product-thumb');
        if (!thumb) {
            return;
        }
        event.preventDefault();
        const index = Number(thumb.dataset.index);
        if (!Number.isNaN(index)) {
            setPreviewByIndex(index);
        }
    });

    syncPreview();
    syncThumbs();
    setUploadNote('');
}
