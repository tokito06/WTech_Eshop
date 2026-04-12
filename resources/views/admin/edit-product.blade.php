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
                    @php $currentImage = $product->images->first(); @endphp
                    @if($currentImage)
                        <img class="add-product-photo__preview" id="photo-preview"
                             src="{{ $currentImage->url }}" alt="{{ $product->name }}"
                             style="display:block">
                        <span class="add-product-photo__label" id="photo-label" style="display:none">Photo</span>
                    @else
                        <span class="add-product-photo__label" id="photo-label">Photo</span>
                        <img class="add-product-photo__preview" id="photo-preview" alt="Product photo preview">
                    @endif
                    <input type="file" id="photo-input" name="image" accept="image/*" hidden>
                </div>

                @if($product->images->count() > 1)
                <div class="add-product-thumbs mt-2">
                    @foreach($product->images as $img)
                    <div class="add-product-thumb {{ $loop->first ? 'active' : '' }}">
                        <img class="img__container" src="{{ $img->url }}" alt="Thumbnail">
                    </div>
                    @endforeach
                </div>
                @endif

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
                    @error('name')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <textarea class="add-product-textarea @error('description') add-product-input--error @enderror"
                              name="description" placeholder="Describing: material, colour, etc."
                              rows="3">{{ old('description', $product->description) }}</textarea>
                    @error('description')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('category_id') add-product-input--error @enderror"
                            name="category_id" required>
                        <option value="" disabled>Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('sex') add-product-input--error @enderror"
                            name="sex" required>
                        @foreach(['men' => 'Men', 'women' => 'Women', 'kids' => 'Kids', 'unisex' => 'Unisex'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('sex', $product->sex) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('sex')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('brand_id') add-product-input--error @enderror"
                            name="brand_id" required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <select class="add-product-input add-product-select @error('status') add-product-input--error @enderror"
                            name="status" required>
                        <option value="active"   {{ old('status', $product->status) === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ old('status', $product->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <input class="add-product-input @error('price') add-product-input--error @enderror"
                           type="number" name="price"
                           value="{{ old('price', $variantsBySize->first()?->price ?? '') }}"
                           step="0.01" min="0" placeholder="Price (€)" required>
                    @error('price')<small style="color:#e05555">{{ $message }}</small>@enderror

                    <button class="add-product-btn" type="submit">Save</button>

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
        if (photoLabel) photoLabel.style.display = 'none';
    }
</script>
@endsection
