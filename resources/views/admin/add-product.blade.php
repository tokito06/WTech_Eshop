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

        </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<style>
.add-product-select {
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
}
</style>
<script>
    const photoDrop    = document.getElementById('photo-drop');
    const photoInput   = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');
    const photoLabel   = document.getElementById('photo-label');

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
    }
</script>
@endsection
