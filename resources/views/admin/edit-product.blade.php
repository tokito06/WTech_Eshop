@extends('layouts.admin')

@section('title', 'Admin — Edit Product')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/addingproduct.css') }}">
@endsection

@section('content')
<main class="add-product-main">
    <div class="container-fluid px-4 px-md-5">
        <div class="row g-4 g-xl-5 align-items-start justify-content-center">

            <!-- Left: photo + size/total -->
            <div class="col-12 col-md-5 col-lg-5 d-flex flex-column">

                <div class="add-product-photo" id="photo-drop" title="Click or drag to upload">
                    <span class="add-product-photo__label" id="photo-label">Photo</span>
                    <img class="add-product-photo__preview" id="photo-preview" src="{{ asset('images/image_1.jpg') }}" alt="Product photo preview" style="display: none;">
                    <input type="file" id="photo-input" accept="image/*" hidden>
                </div>

                <div class="add-product-thumbs" id="thumbs-row">
                    <div class="add-product-thumb active" data-index="0"><img class="img__container" src="{{ asset('images/image_1.jpg') }}" alt="Thumbnail 1"></div>
                    <div class="add-product-thumb" data-index="1"><img class="img__container" src="{{ asset('images/image_2.jpg') }}" alt="Thumbnail 2"></div>
                    <div class="add-product-thumb" data-index="2"><img class="img__container" src="{{ asset('images/image_3.jpg') }}" alt="Thumbnail 3"></div>
                    <div class="add-product-thumb" data-index="3"><img class="img__container" src="{{ asset('images/image_1.jpg') }}" alt="Thumbnail 4"></div>
                    <div class="add-product-thumb" data-index="4"><img class="img__container" src="{{ asset('images/image_2.jpg') }}" alt="Thumbnail 5"></div>
                </div>

                <div class="d-flex gap-3 mt-3">
                    <input class="add-product-input add-product-input--half" type="text" placeholder="Size">
                    <input class="add-product-input add-product-input--half" type="number" placeholder="Total" min="0">
                </div>
            </div>

            <!-- Right: fields + save button -->
            <div class="col-12 col-md-7 col-lg-5">
                <div class="add-product-fields">

                    <input class="add-product-input" type="text" id="product-name" placeholder="Name" required>

                    <textarea class="add-product-textarea" id="product-desc"
                              placeholder="Describing: material , colour , etc." rows="3"></textarea>

                    <div class="d-flex gap-3">
                        <input class="add-product-input add-product-input--half" type="text" placeholder="Type">
                    </div>

                    <div class="d-flex gap-3">
                        <input class="add-product-input add-product-input--half" type="text" placeholder="Sex">
                    </div>

                    <input class="add-product-input add-product-input--vendor" type="text" placeholder="Vendor">

                    <button class="add-product-btn" id="add-btn">Save</button>

                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@section('scripts')
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
        if (file && file.type.startsWith('image/')) showPreview(file);
    });
    photoInput.addEventListener('change', () => { if (photoInput.files[0]) showPreview(photoInput.files[0]); });

    function showPreview(file) {
        photoPreview.src = URL.createObjectURL(file);
        photoPreview.style.display = 'block';
        photoLabel.style.display   = 'none';
    }

    document.getElementById('thumbs-row').addEventListener('click', e => {
        const thumb = e.target.closest('.add-product-thumb');
        if (!thumb) return;
        document.querySelectorAll('.add-product-thumb').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    });

    document.getElementById('add-btn').addEventListener('click', () => {
        const name = document.getElementById('product-name');
        if (!name.value.trim()) {
            name.classList.add('add-product-input--error');
            name.focus();
        } else {
            name.classList.remove('add-product-input--error');
            alert('Product saved!');
        }
    });

    document.getElementById('product-name').addEventListener('input', function () {
        this.classList.remove('add-product-input--error');
    });
</script>
@endsection
