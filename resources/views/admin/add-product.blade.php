@extends('layouts.admin')

@section('title', 'Admin — Add Product')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/addingproduct.css') }}">
@endsection

@section('content')
<main class="add-product-main">
    <div class="container-fluid px-4 px-md-5">

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data">
        @csrf

        <div class="row g-4 g-xl-5 align-items-start justify-content-center">

            <!-- Left: photo + sizes -->
            <div class="col-12 col-md-5 col-lg-4 d-flex flex-column">

                <div class="add-product-photo" id="photo-drop" title="Click or drag to upload">
                    <span class="add-product-photo__label" id="photo-label">Photo</span>
                    <img class="add-product-photo__preview" id="photo-preview" alt="Product photo preview">
                    <input type="file" id="photo-input" name="image" accept="image/*" hidden>
                </div>

                <!-- Size inventory table -->
                <div class="mt-3">
                    <p class="mb-2" style="font-size:clamp(0.8rem,0.9vw,1rem);color:var(--dark-gray-color)">
                        Size inventory (0 = unavailable)
                    </p>
                    @foreach(['XS','S','M','L','XL','XXL'] as $size)
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span style="width:36px;font-weight:600;font-size:clamp(0.85rem,0.95vw,1.05rem)">
                            {{ $size }}
                        </span>
                        <input class="add-product-input add-product-input--half"
                               type="number" name="inventory[{{ $size }}]"
                               value="{{ old('inventory.' . $size, 0) }}"
                               min="0" placeholder="0">
                    </div>
                    @endforeach
                </div>

            </div>

            <!-- Right: fields -->
            <div class="col-12 col-md-7 col-lg-5">
                <div class="add-product-fields">

                    <input class="add-product-input @error('name') add-product-input--error @enderror"
                           type="text" name="name" value="{{ old('name') }}"
                           placeholder="Name" required>
                    @error('name')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <textarea class="add-product-textarea @error('description') add-product-input--error @enderror"
                              name="description" placeholder="Describing: material, colour, etc."
                              rows="3">{{ old('description') }}</textarea>
                    @error('description')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('category_id') add-product-input--error @enderror"
                            name="category_id" required>
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('sex') add-product-input--error @enderror"
                            name="sex" required>
                        <option value="" disabled {{ old('sex') ? '' : 'selected' }}>Target audience</option>
                        <option value="men" {{ old('sex') === 'men'    ? 'selected' : '' }}>Men</option>
                        <option value="women" {{ old('sex') === 'women'  ? 'selected' : '' }}>Women</option>
                        <option value="kids" {{ old('sex') === 'kids'   ? 'selected' : '' }}>Kids</option>
                        <option value="unisex" {{ old('sex') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                    </select>
                    @error('sex')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('brand_id') add-product-input--error @enderror"
                            name="brand_id" required>
                        <option value="" disabled {{ old('brand_id') ? '' : 'selected' }}>Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')<small style="color:#e05555">{{ $message }}</small>@enderror

                    @if($brands->isEmpty())
                        <small style="color:#e05555">
                            You have no brands yet.
                            <a href="{{ route('admin.brands') }}">Create a brand first →</a>
                        </small>
                    @endif

                    <input class="add-product-input @error('price') add-product-input--error @enderror"
                           type="number" name="price" value="{{ old('price') }}"
                           step="0.01" min="0" placeholder="Price (€)" required>
                    @error('price')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <button class="add-product-btn" type="submit">Add</button>

                </div>
            </div>

            <!-- Live preview -->
            <div class="col-12 col-lg-3">
                <div class="add-product-preview">
                    <h3 class="add-product-preview__title">Preview</h3>
                    <div class="add-product-preview__photo" id="preview-photo">
                        <span class="add-product-preview__placeholder">No photo</span>
                    </div>
                    <div class="add-product-preview__row">
                        <span class="add-product-preview__label">Name</span>
                        <span class="add-product-preview__value" id="preview-name">—</span>
                    </div>
                    <div class="add-product-preview__row">
                        <span class="add-product-preview__label">Description</span>
                        <span class="add-product-preview__value" id="preview-description">—</span>
                    </div>
                    <div class="add-product-preview__row">
                        <span class="add-product-preview__label">Category</span>
                        <span class="add-product-preview__value" id="preview-category">—</span>
                    </div>
                    <div class="add-product-preview__row">
                        <span class="add-product-preview__label">Audience</span>
                        <span class="add-product-preview__value" id="preview-sex">—</span>
                    </div>
                    <div class="add-product-preview__row">
                        <span class="add-product-preview__label">Brand</span>
                        <span class="add-product-preview__value" id="preview-brand">—</span>
                    </div>
                    <div class="add-product-preview__row">
                        <span class="add-product-preview__label">Price</span>
                        <span class="add-product-preview__value" id="preview-price">—</span>
                    </div>
                    <div class="add-product-preview__row">
                        <span class="add-product-preview__label">Sizes</span>
                        <span class="add-product-preview__value" id="preview-sizes">—</span>
                    </div>
                </div>
            </div>

        </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    const photoDrop    = document.getElementById('photo-drop');
    const photoInput   = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');
    const photoLabel   = document.getElementById('photo-label');

    const previewPhotoWrap = document.getElementById('preview-photo');
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

    function syncPreviewText(el, value) {
        if (!el) return;
        el.textContent = value && value.trim() ? value.trim() : '—';
    }

    function syncSelectText(el, select) {
        if (!el || !select) return;
        const option = select.selectedOptions[0];
        const text = option && option.value ? option.textContent.trim() : '';
        el.textContent = text || '—';
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
        previewSizes.textContent = selected.length ? selected.join(', ') : '—';
    }

    function syncPreview() {
        syncPreviewText(previewName, nameInput?.value || '');
        syncPreviewText(previewDescription, descriptionInput?.value || '');
        syncSelectText(previewCategory, categorySelect);
        syncSelectText(previewSex, sexSelect);
        syncSelectText(previewBrand, brandSelect);
        const price = priceInput?.value ? `${Number(priceInput.value).toFixed(2)} €` : '';
        previewPrice.textContent = price || '—';
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
    photoDrop.addEventListener('dragover', e => { e.preventDefault(); photoDrop.classList.add('drag-over'); });
    photoDrop.addEventListener('dragleave', () => photoDrop.classList.remove('drag-over'));
    photoDrop.addEventListener('drop', e => {
        e.preventDefault();
        photoDrop.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            photoInput.files = e.dataTransfer.files;
            showPreview(file);
        }
    });
    photoInput.addEventListener('change', () => { if (photoInput.files[0]) showPreview(photoInput.files[0]); });

    function showPreview(file) {
        photoPreview.src = URL.createObjectURL(file);
        photoPreview.style.display = 'block';
        photoLabel.style.display = 'none';

        const img = document.createElement('img');
        img.src = photoPreview.src;
        previewPhotoWrap.innerHTML = '';
        previewPhotoWrap.appendChild(img);
    }

    syncPreview();
</script>
@endsection
