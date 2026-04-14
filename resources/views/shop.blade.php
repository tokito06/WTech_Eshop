@extends('layouts.app')

@section('title', 'Shop')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/main_page.css') }}">
@endsection

@section('subnav')
<x-gender-subnav route-name="shop" :active-sex="$activeSex ?? null" />
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
                <div class="col-6 col-lg-4 col-xxl-3">
                    <x-product-card :product="$product" :show-sizes="false" />
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
