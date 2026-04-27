@props([
    'product',
    'showSizes' => false,
])

@php
    $image = $product->images->first();
    $imageUrl = $image ? $image->url : asset('images/image_1.jpg');
    $variants = $product->variants->take(4);
    $price = $product->variants->min('price') ?? 0;
    $isOutOfStock = ((int) $product->variants->sum('inventory')) <= 0;
    $isFavourited = auth()->check()
        ? (bool) ($product->is_favourited ?? $product->isFavouritedBy(auth()->user()))
        : false;
@endphp

<div @class(['product-card', 'product-card--out-of-stock' => $isOutOfStock])>
    <button
        class="product-card__fav {{ $isFavourited ? 'liked' : '' }}"
        data-favourite-toggle
        data-product-id="{{ $product->id }}"
        data-favourited="{{ $isFavourited ? '1' : '0' }}"
        aria-label="{{ $isFavourited ? 'Remove from favourites' : 'Add to favourites' }}"
    >
        <span class="material-symbols-outlined">{{ $isFavourited ? 'favorite' : 'favorite_border' }}</span>
    </button>
    <a href="{{ route('product', $product) }}" class="clear-link">
        <div class="product-card__img">
            <img src="{{ $imageUrl }}" alt="{{ $product->name }} image">
            @if($showSizes)
                <div class="product-card__sizes">
                    @foreach($variants as $variant)
                        <span @class([
                            'product-card__size-tag',
                            'product-card__size-tag--out-of-stock' => ((int) $variant->inventory) <= 0,
                        ])>{{ $variant->symbol }}</span>
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
