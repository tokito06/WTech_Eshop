@extends('layouts.app')

@section('title', 'Shop')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/main_page.css') }}">
@endsection

@section('subnav')
@php
    $sexLabels = [
        'men' => 'Men',
        'women' => 'Women',
        'kids' => 'Kids',
    ];
@endphp

<!-- Category Nav -->
<nav class="category-nav">
    <ul class="nav d-flex flex-nowrap overflow-auto" style="scrollbar-width:none;">
        @foreach($sexLabels as $value => $label)
            <li class="nav-item">
                <a class="nav-link {{ ($activeSex ?? null) === $value ? 'active' : '' }}" href="{{ route('shop', array_merge(request()->except('page'), ['sex' => $value])) }}">{{ $label }}</a>
            </li>
        @endforeach
    </ul>
</nav>
@endsection

@section('content')
<main>
    <div class="container py-4">

        <!-- Search input -->
        <form class="shop-toolbar" method="get" action="{{ route('search') }}">
            <input class="shop-search" type="search" name="q" placeholder="Type your query here...." value="{{ request('q') }}" aria-label="Search products">
            <button type="submit" class="shop-search-btn" aria-label="Search products" title="Search">
                <span class="material-symbols-outlined" aria-hidden="true">search</span>
            </button>
        </form>

        <!-- Product Grid -->
        <div class="row g-3 g-md-4">
            @forelse($products as $product)
                @php
                    $image = $product->images->first();
                    $imageUrl = $image ? $image->url : asset('images/image_1.jpg');
                    $price = $product->variants->min('price') ?? 0;
                @endphp
                <div class="col-6 col-lg-4 col-xxl-3">
                    <div class="product-card">
                        <button class="product-card__fav"><span class="material-symbols-outlined">favorite</span></button>
                        <a href="{{ route('product', $product) }}" class="clear-link">
                            <div class="product-card__img">
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }} image">
                            </div>
                            <div class="product-card__body">
                                <h3 class="product-card__name">{{ $product->name }}</h3>
                                <p class="product-card__desc">{{ \Illuminate\Support\Str::limit($product->description, 120) }}</p>
                                <span class="product-card__price">{{ number_format((float) $price, 2, ',', ' ') }} €</span>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted mb-0">No products in catalogue yet.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.product-card__fav').forEach(btn => {
        btn.addEventListener('click', () => btn.classList.toggle('liked'));
    });
</script>
@endsection
