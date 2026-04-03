@extends('layouts.app')

@section('title', 'Liked')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/favourites.css') }}">
@endsection

@section('content')
<main class="liked-section">
    <div class="container">

        <div class="liked-title">
            <span class="material-symbols-outlined">favorite</span>
            <h1>Liked</h1>
        </div>

        <div class="liked-empty" id="liked-empty">
            <span class="material-symbols-outlined">heart_broken</span>
            <p>No liked items yet</p>
        </div>

        <div class="row g-3 g-md-4" id="liked-grid">

            <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                <div class="product-card">
                    <button class="product-card__delete" aria-label="Remove from liked"><span class="material-symbols-outlined">delete</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img"><img src="{{ asset('images/image_1.jpg') }}" alt="Liked product image"></div>
                        <div class="product-card__body">
                            <h2 class="product-card__name">Super View Glasses</h2>
                            <p class="product-card__desc">UV-protective lenses with a lightweight frame. Wide field of view for everyday outdoor wear.</p>
                            <span class="product-card__price">19,99 €</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                <div class="product-card">
                    <button class="product-card__delete" aria-label="Remove from liked"><span class="material-symbols-outlined">delete</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img"><img src="{{ asset('images/image_2.jpg') }}" alt="Liked product image"></div>
                        <div class="product-card__body">
                            <h2 class="product-card__name">Red Jacket</h2>
                            <p class="product-card__desc">Bold red jacket with a modern slim cut. Water-resistant fabric, perfect for spring and autumn.</p>
                            <span class="product-card__price">19,99 €</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                <div class="product-card">
                    <button class="product-card__delete" aria-label="Remove from liked"><span class="material-symbols-outlined">delete</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img"><img src="{{ asset('images/image_3.jpg') }}" alt="Liked product image"></div>
                        <div class="product-card__body">
                            <h2 class="product-card__name">White T-shirt</h2>
                            <p class="product-card__desc">Classic 100% cotton tee with a relaxed fit. Soft, breathable and versatile for any casual look.</p>
                            <span class="product-card__price">19,99 €</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                <div class="product-card">
                    <button class="product-card__delete" aria-label="Remove from liked"><span class="material-symbols-outlined">delete</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img"><img src="{{ asset('images/image_1.jpg') }}" alt="Liked product image"></div>
                        <div class="product-card__body">
                            <h2 class="product-card__name">Super View Glasses</h2>
                            <p class="product-card__desc">Polarised lenses in a sleek frame. Blocks glare and protects eyes on sunny days.</p>
                            <span class="product-card__price">34,50 €</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                <div class="product-card">
                    <button class="product-card__delete" aria-label="Remove from liked"><span class="material-symbols-outlined">delete</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img"><img src="{{ asset('images/image_2.jpg') }}" alt="Liked product image"></div>
                        <div class="product-card__body">
                            <h2 class="product-card__name">Red Jacket</h2>
                            <p class="product-card__desc">Oversized fit with ribbed cuffs and a zip-up front. Warm lining for extra comfort in cold weather.</p>
                            <span class="product-card__price">59,00 €</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                <div class="product-card">
                    <button class="product-card__delete" aria-label="Remove from liked"><span class="material-symbols-outlined">delete</span></button>
                    <a href="{{ route('product') }}" class="clear-link">
                        <div class="product-card__img"><img src="{{ asset('images/image_3.jpg') }}" alt="Liked product image"></div>
                        <div class="product-card__body">
                            <h2 class="product-card__name">White T-shirt</h2>
                            <p class="product-card__desc">Crew-neck essential in premium cotton. Pre-washed fabric that stays soft wash after wash.</p>
                            <span class="product-card__price">12,99 €</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.getElementById('liked-grid').addEventListener('click', e => {
        const btn = e.target.closest('.product-card__delete');
        if (!btn) return;
        const col = btn.closest('[class*="col-"]');
        col.style.transition = 'opacity 0.25s, transform 0.25s';
        col.style.opacity    = '0';
        col.style.transform  = 'scale(0.9)';
        setTimeout(() => { col.remove(); checkEmpty(); }, 260);
    });

    function checkEmpty() {
        const remaining = document.querySelectorAll('#liked-grid [class*="col-"]').length;
        document.getElementById('liked-empty').style.display = remaining === 0 ? 'flex' : 'none';
    }
</script>
@endsection
