@extends('layouts.app')

@section('title', 'Liked')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/favourites.css') }}">
@endsection

@section('content')
<main class="liked-section">
    <div class="container">
        @php
            $favourites = $favourites ?? collect();
        @endphp

        <div class="liked-title">
            <span class="material-symbols-outlined">favorite</span>
            <h1>Liked</h1>
        </div>

        <div class="liked-empty" id="liked-empty" @style(['display: flex' => $favourites->isEmpty()])>
            <span class="material-symbols-outlined">heart_broken</span>
            <p>No liked items yet</p>
        </div>

        <div class="row g-3 g-md-4" id="liked-grid">
            @foreach($favourites as $product)
                @php
                    $imageUrl = $product->first_image_url ?: asset('images/image_1.jpg');
                    $price = $product->min_price ?? 0;
                @endphp
                <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                    <div class="product-card">
                        <button
                            class="product-card__delete js-remove-favourite"
                            aria-label="Remove from liked"
                            data-remove-url="{{ route('favourites.remove', $product) }}"
                        ><span class="material-symbols-outlined">delete</span></button>
                        <a href="{{ route('product', $product) }}" class="clear-link">
                            <div class="product-card__img"><img src="{{ $imageUrl }}" alt="{{ $product->name }} image"></div>
                            <div class="product-card__body">
                                <h2 class="product-card__name">{{ $product->name }}</h2>
                                <p class="product-card__desc">{{ \Illuminate\Support\Str::limit($product->description, 120) }}</p>
                                <span class="product-card__price">{{ number_format((float) $price, 2, ',', ' ') }} €</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    const likedGrid = document.getElementById('liked-grid');
    const likedEmpty = document.getElementById('liked-empty');

    likedGrid?.addEventListener('click', async e => {
        const btn = e.target.closest('.js-remove-favourite');
        if (!btn) return;

        const removeUrl = btn.dataset.removeUrl;
        if (!removeUrl) return;

        try {
            const response = await fetch(removeUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            if (!response.ok) {
                throw new Error('Failed to remove');
            }

            const col = btn.closest('[class*="col-"]');
            if (col) {
                col.style.transition = 'opacity 0.25s, transform 0.25s';
                col.style.opacity = '0';
                col.style.transform = 'scale(0.9)';

                setTimeout(() => {
                    col.remove();
                    const remaining = likedGrid.querySelectorAll('[class*="col-"]').length;
                    likedEmpty.style.display = remaining === 0 ? 'flex' : 'none';
                }, 260);
            }
        } catch (error) {
            console.error(error);
        }
    });
</script>
@endsection
