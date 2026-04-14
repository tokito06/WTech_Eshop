@props([
    'product',
    'showSizes' => false,
])

@php
    $image = $product->images->first();
    $imageUrl = $image ? $image->url : asset('images/image_1.jpg');
    $sizes = $product->variants->pluck('symbol')->take(4);
    $price = $product->variants->min('price') ?? 0;
@endphp

<div class="product-card">
    <button class="product-card__fav" aria-label="Like">
        <span class="material-symbols-outlined">favorite</span>
    </button>
    <a href="{{ route('product', $product) }}" class="clear-link">
        <div class="product-card__img">
            <img src="{{ $imageUrl }}" alt="{{ $product->name }} image">
            @if($showSizes)
                <div class="product-card__sizes">
                    @foreach($sizes as $size)
                        <span class="product-card__size-tag">{{ $size }}</span>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="product-card__body">
            <h3 class="product-card__name">{{ $product->name }}</h3>
            <p class="product-card__desc">{{ \Illuminate\Support\Str::limit($product->description, 120) }}</p>
            <span class="product-card__price">{{ number_format((float) $price, 2, ',', ' ') }} €</span>
        </div>
    </a>
</div>
