@extends('layouts.admin')

@section('title', 'Admin — Edit Product')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/addingproduct.css') }}">
@endsection

@section('content')
<main class="add-product-main">
    <div class="container-fluid px-4 px-md-5">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.update', $product) }}"
              enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4 g-xl-5 align-items-start justify-content-center">

            <!-- Left: photo + sizes -->
            <div class="col-12 col-md-5 col-lg-4 d-flex flex-column">

                <div class="add-product-photo" id="photo-drop" title="Click or drag to upload">
                    <span class="add-product-photo__label" id="photo-label">Photo</span>
                    <img class="add-product-photo__preview" id="photo-preview" alt="Product photo preview">
                    <input type="file" id="photo-input" name="images[]" accept="image/*" multiple hidden>
                </div>

                <div class="add-product-upload-note" id="photo-upload-note" role="status" aria-live="polite" hidden></div>

                 <div class="add-product-thumbs" id="photo-thumbs"
                     data-existing-thumbs='@json($product->images->map(fn($img) => $img->url)->values())'>
                    <div class="add-product-thumb add-product-thumb--empty" id="photo-thumbs-empty">
                        <span class="add-product-thumb__label">No photos yet</span>
                    </div>
                </div>

                <!-- Size inventory -->
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
                               value="{{ old('inventory.' . $size, $variantsBySize[$size]->inventory ?? 0) }}"
                               min="0" placeholder="0">
                    </div>
                    @endforeach
                </div>

            </div>

            <!-- Right: fields -->
            <div class="col-12 col-md-7 col-lg-5">
                <div class="add-product-fields">

                    <input class="add-product-input @error('name') add-product-input--error @enderror"
                           type="text" name="name"
                           value="{{ old('name', $product->name) }}"
                           placeholder="Name" required>
                    @error('name')<small class="error-text">{{ $message }}</small>@enderror

                    <textarea class="add-product-textarea @error('description') add-product-input--error @enderror"
                              name="description" placeholder="Describing: material, colour, etc."
                              rows="3">{{ old('description', $product->description) }}</textarea>
                    @error('description')<small class="error-text">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('category_id') add-product-input--error @enderror"
                            name="category_id" required>
                        <option value="" disabled {{ old('category_id', $product->category_id) ? '' : 'selected' }}>Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<small class="error-text">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('sex') add-product-input--error @enderror"
                            name="sex" required>
                        <option value="" disabled {{ old('sex', $product->sex) ? '' : 'selected' }}>Target audience</option>
                        <option value="men" {{ old('sex', $product->sex) === 'men' ? 'selected' : '' }}>Men</option>
                        <option value="women" {{ old('sex', $product->sex) === 'women' ? 'selected' : '' }}>Women</option>
                        <option value="kids" {{ old('sex', $product->sex) === 'kids' ? 'selected' : '' }}>Kids</option>
                        <option value="unisex" {{ old('sex', $product->sex) === 'unisex' ? 'selected' : '' }}>Unisex</option>
                    </select>
                    @error('sex')<small class="error-text">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('brand_id') add-product-input--error @enderror"
                            name="brand_id" required>
                        <option value="" disabled {{ old('brand_id', $product->brand_id) ? '' : 'selected' }}>Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')<small class="error-text">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('status') add-product-input--error @enderror"
                            name="status" required>
                        <option value="active"   {{ old('status', $product->status) === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ old('status', $product->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')<small class="error-text">{{ $message }}</small>@enderror

                    <input class="add-product-input @error('price') add-product-input--error @enderror"
                           type="number" name="price"
                           value="{{ old('price', $variantsBySize->first()?->price ?? '') }}"
                           step="0.01" min="0" placeholder="Price (€)" required>
                    @error('price')<small class="error-text">{{ $message }}</small>@enderror

                    <button class="add-product-btn" type="submit">Save</button>

                </div>
            </div>

            <!-- Live preview -->
            <div class="col-12 col-lg-3">
                <div class="add-product-preview">
                    <h3 class="add-product-preview__title">Preview</h3>
                    <div class="add-product-preview__photo" id="preview-photo">
                        <button type="button" class="add-product-preview__nav" id="preview-prev" aria-label="Previous photo">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <button type="button" class="add-product-preview__nav" id="preview-next" aria-label="Next photo">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                        <img class="add-product-preview__image" id="preview-image" alt="Preview photo" hidden>
                        <span class="add-product-preview__placeholder" id="preview-placeholder">No photo</span>
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