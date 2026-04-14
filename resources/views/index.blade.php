@extends('layouts.app')

@section('title', 'Home')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<main>

    <!-- Promo banner -->
    <section class="home-banner-section">
        <div class="container">
            <div class="home-banner-scroll" id="home-banner-scroll" aria-label="Promotions">
                @forelse($banners as $banner)
                <a class="home-banner-card clear-link" href="{{ route('shop') }}">
                    @if($banner->image)
                    <div class="home-banner-card__media">
                        <img class="img__container" src="{{ $banner->image->url }}" alt="{{ $banner->name }}">
                    </div>
                    @endif
                    <div class="home-banner-card__content">
                        <p class="home-banner-card__eyebrow">Featured</p>
                        <h2>{{ $banner->name }}</h2>
                        <p>{{ $banner->description }}</p>
                    </div>
                </a>
                @empty
                <a class="home-banner-card clear-link" href="{{ route('shop') }}">
                    <div class="home-banner-card__content">
                        <p class="home-banner-card__eyebrow">New Collection</p>
                        <h2>Fresh Arrivals</h2>
                        <p>Discover trending items and complete your look.</p>
                    </div>
                </a>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Categories Scroll -->
    <section class="home-section categories-section">
        <div class="container">
            <h2 class="section-title">Explore our categories</h2>
            <div class="categories-wrapper">
                <button class="cat-arrow cat-arrow--prev" id="cat-prev" aria-label="Previous">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="cat-arrow cat-arrow--next" id="cat-next" aria-label="Next">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
                <div class="categories-inner">
                    <div class="categories-scroll" id="cat-scroll">
                        @foreach($categories as $category)
                        <a class="cat-card clear-link" href="{{ route('search') }}?category_id={{ $category->id }}">
                            <div class="cat-card__img">
                                <span class="material-symbols-outlined cat-card__icon" aria-hidden="true">{{ $category->icon }}</span>
                            </div>
                            <span class="cat-card__label">{{ $category->name }}</span>
                        </a>
                        @endforeach
                    </div>
                    <div class="cat-dots">
                        <button class="cat-dot active" data-page="0"></button>
                        <button class="cat-dot"        data-page="1"></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New for Men -->
    @if($menProducts->isNotEmpty())
    <section class="home-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title mb-0">New for Men</h2>
                <a href="{{ route('shop') }}?sex=men" class="explore-link">
                    See all <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="row g-3 g-md-4">
                @foreach($menProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <x-product-card :product="$product" :show-sizes="false" />
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- New for Women -->
    @if($womenProducts->isNotEmpty())
    <section class="home-section home-section--soft">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title mb-0">New for Women</h2>
                <a href="{{ route('shop') }}?sex=women" class="explore-link">
                    See all <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="row g-3 g-md-4">
                @foreach($womenProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <x-product-card :product="$product" :show-sizes="false" />
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Trending now -->
    @if($trendingProducts->isNotEmpty())
    <section class="home-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title mb-0">Trending now</h2>
                <a href="{{ route('shop') }}" class="explore-link">
                    See all <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="row g-3 g-md-4">
                @foreach($trendingProducts as $product)
                <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                    <x-product-card :product="$product" :show-sizes="false" />
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</main>
@endsection

@section('scripts')
<script>
    const catScroll = document.getElementById('cat-scroll');
    const dots      = document.querySelectorAll('.cat-dot');
    const prevBtn   = document.getElementById('cat-prev');
    const nextBtn   = document.getElementById('cat-next');
    const bannerScroll = document.getElementById('home-banner-scroll');

    if (bannerScroll) {
        const banners = bannerScroll.querySelectorAll('.home-banner-card');
        let bannerIndex = 0;
        let bannerTimer = null;

        const goToBanner = (i) => bannerScroll.scrollTo({ left: i * bannerScroll.clientWidth, behavior: 'smooth' });
        const stopAuto   = () => { if (bannerTimer) { clearInterval(bannerTimer); bannerTimer = null; } };
        const startAuto  = () => {
            if (banners.length < 2) return;
            stopAuto();
            bannerTimer = setInterval(() => { bannerIndex = (bannerIndex + 1) % banners.length; goToBanner(bannerIndex); }, 3600);
        };

        setTimeout(startAuto, 1500);
        bannerScroll.addEventListener('scroll', () => {
            const page = Math.round(bannerScroll.scrollLeft / Math.max(1, bannerScroll.clientWidth));
            bannerIndex = Math.max(0, Math.min(banners.length - 1, page));
        });
        bannerScroll.addEventListener('pointerdown', stopAuto);
        bannerScroll.addEventListener('mouseenter', stopAuto);
        bannerScroll.addEventListener('mouseleave', () => setTimeout(startAuto, 1200));
        window.addEventListener('resize', () => goToBanner(bannerIndex));
    }

    function positionArrows() {
        const top    = catScroll.offsetTop;
        const center = top + catScroll.offsetHeight / 2 - prevBtn.offsetHeight / 2;
        prevBtn.style.top = center + 'px';
        nextBtn.style.top = center + 'px';
    }

    function updateScrollState() {
        const canScroll = catScroll.scrollWidth > catScroll.clientWidth + 2;
        catScroll.classList.toggle('no-scroll', !canScroll);
        prevBtn.classList.toggle('hidden', !canScroll || catScroll.scrollLeft <= 0);
        nextBtn.classList.toggle('hidden', !canScroll || catScroll.scrollLeft >= catScroll.scrollWidth - catScroll.clientWidth - 2);
        document.querySelector('.cat-dots').style.visibility = canScroll ? 'visible' : 'hidden';
    }

    prevBtn.addEventListener('click', () => catScroll.scrollBy({ left: -catScroll.offsetWidth * 0.8, behavior: 'smooth' }));
    nextBtn.addEventListener('click', () => catScroll.scrollBy({ left:  catScroll.offsetWidth * 0.8, behavior: 'smooth' }));

    catScroll.addEventListener('scroll', () => {
        const max   = catScroll.scrollWidth - catScroll.clientWidth;
        const ratio = max > 0 ? catScroll.scrollLeft / max : 0;
        dots.forEach((d, i) => d.classList.toggle('active', i === (ratio < 0.5 ? 0 : 1)));
        updateScrollState();
    });

    dots.forEach(dot => dot.addEventListener('click', () => {
        catScroll.scrollTo({ left: parseInt(dot.dataset.page) * (catScroll.scrollWidth - catScroll.clientWidth), behavior: 'smooth' });
    }));

    positionArrows();
    updateScrollState();
    window.addEventListener('resize', () => { positionArrows(); updateScrollState(); });

    document.querySelectorAll('.product-card__fav').forEach(btn =>
        btn.addEventListener('click', () => btn.classList.toggle('liked'))
    );
</script>
@endsection