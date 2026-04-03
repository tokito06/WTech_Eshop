@extends('layouts.app')

@section('title', 'Product')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('subnav')
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                <li class="breadcrumb-item"><a href="{{ route('search') }}">Mens</a></li>
                <li class="breadcrumb-item"><a href="{{ route('search') }}">Outwear</a></li>
                <li class="breadcrumb-item active" aria-current="page">Red Jacket</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<main class="product-section">
    <div class="container">
        <div class="row g-4 g-xl-5 align-items-start">

            <!-- Photos Carousel -->
            <div class="col-12 col-md-6">
                <div id="productCarousel" class="carousel slide product-carousel" data-bs-touch="true">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="3"></button>
                    </div>

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="carousel-slide"><img class="img__container" src="{{ asset('images/image_2.jpg') }}" alt="Product photo 1"></div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-slide"><img class="img__container" src="{{ asset('images/image_3.jpg') }}" alt="Product photo 2"></div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-slide"><img class="img__container" src="{{ asset('images/image_1.jpg') }}" alt="Product photo 3"></div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-slide"><img class="img__container" src="{{ asset('images/image_2.jpg') }}" alt="Product photo 4"></div>
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>

                <!-- Thumbnails -->
                <div class="thumbnails-row">
                    <div class="product__thumb active" data-slide="0"><img src="{{ asset('images/image_2.jpg') }}" alt="Thumbnail 1"></div>
                    <div class="product__thumb" data-slide="1"><img src="{{ asset('images/image_3.jpg') }}" alt="Thumbnail 2"></div>
                    <div class="product__thumb" data-slide="2"><img src="{{ asset('images/image_1.jpg') }}" alt="Thumbnail 3"></div>
                    <div class="product__thumb" data-slide="3"><img src="{{ asset('images/image_2.jpg') }}" alt="Thumbnail 4"></div>
                    <div class="product__thumb" data-slide="4"><img src="{{ asset('images/image_1.jpg') }}" alt="Thumbnail 5"></div>
                </div>

                <div class="product__rating">☆ ☆ ☆ ☆ ☆</div>
            </div>

            <!-- Product info -->
            <div class="col-12 col-md-6">
                <div class="product__info">
                    <span class="product__category-badge">Outerwear</span>
                    <h1>Red Jacket</h1>

                    <p class="product__description">
                        Bold red jacket with a modern slim cut. Made from water-resistant
                        ripstop fabric with a warm fleece lining. Features a full zip-up
                        front, ribbed cuffs, and two side pockets. Perfect for spring,
                        autumn, or cool summer evenings.
                    </p>

                    <div class="product__price">104,50 €</div>

                    <select class="product__size">
                        <option>Size</option>
                        <option>XS</option>
                        <option>S</option>
                        <option>M</option>
                        <option>L</option>
                        <option>XL</option>
                        <option>XXL</option>
                    </select>

                    <div class="quantity__container">
                        <div class="quantity__label">Quantity</div>
                        <div class="quantity__selector">
                            <button class="quantity-selector__btn" id="qty-minus" aria-label="Decrease">−</button>
                            <input class="quantity-selector__input" id="qty-input" type="number" value="1" min="1" max="99" aria-label="Quantity">
                            <button class="quantity-selector__btn" id="qty-plus" aria-label="Increase">+</button>
                        </div>
                    </div>

                    <div class="product__actions">
                        <button class="product__fav" title="Add to favourites">♡</button>
                        <button class="product__add" id="add-to-bag">Add to bag</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="cartToast" class="toast align-items-center border-0" role="alert" aria-live="assertive">
        <div class="d-flex align-items-center gap-2 p-3" style="background:var(--highlight-color); border-radius:10px;">
            <span class="material-symbols-outlined">shopping_cart</span>
            <span>Added <strong id="toast-qty">1</strong> item(s) to bag!</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const carousel = document.getElementById('productCarousel');
    const thumbs = document.querySelectorAll('.product__thumb');
    const bsCarousel = bootstrap.Carousel.getOrCreateInstance(carousel);

    thumbs.forEach((thumb, i) => {
        thumb.addEventListener('click', () => { bsCarousel.to(i < 4 ? i : 0); });
    });

    carousel.addEventListener('slid.bs.carousel', e => {
        thumbs.forEach(t => t.classList.remove('active'));
        const idx = e.to < thumbs.length ? e.to : 0;
        thumbs[idx].classList.add('active');
    });

    const qtyInput = document.getElementById('qty-input');
    const qtyMinus = document.getElementById('qty-minus');
    const qtyPlus  = document.getElementById('qty-plus');
    const addBtn   = document.getElementById('add-to-bag');

    function setQty(val) {
        const n = Math.max(1, Math.min(99, parseInt(val) || 1));
        qtyInput.value = n;
        qtyMinus.disabled = n <= 1;
    }

    qtyMinus.addEventListener('click', () => setQty(+qtyInput.value - 1));
    qtyPlus.addEventListener ('click', () => setQty(+qtyInput.value + 1));
    qtyInput.addEventListener('input', () => setQty(qtyInput.value));

    addBtn.addEventListener('click', () => {
        document.getElementById('toast-qty').textContent = qtyInput.value;
        bootstrap.Toast.getOrCreateInstance(document.getElementById('cartToast'), { delay: 2500 }).show();
    });

    setQty(1);

    const stars = document.querySelector('.product__rating');
    let rating = 0;
    stars.addEventListener('click', e => {
        const rect = stars.getBoundingClientRect();
        rating = Math.ceil((e.clientX - rect.left) / rect.width * 5);
        stars.textContent = '★'.repeat(rating) + '☆'.repeat(5 - rating);
    });
</script>
@endsection
