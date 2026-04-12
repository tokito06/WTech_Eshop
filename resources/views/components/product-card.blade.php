<div class="product-card">
    <button class="product-card__fav" aria-label="Like">
        <span class="material-symbols-outlined">favorite</span>
    </button>
    <a href="{{ route('product') }}?id={{ $product->id }}" class="clear-link">
        <div class="product-card__img">
            <img src="{{ $product->firstImageUrl ?? asset('images/image_1.jpg') }}" alt="{{ $product->name }}">
        </div>
        <div class="product-card__body">
            <h3 class="product-card__name">{{ $product->name }}</h3>
            <p class="product-card__desc">{{ $product->description }}</p>
            @if($product->minPrice !== null)
            <span class="product-card__price">{{ number_format($product->minPrice, 2, ',', '.') }} €</span>
            @endif
        </div>
    </a>
</div>
