@extends('layouts.app')

@section('title', 'Home')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('subnav')
<!-- Category Nav -->
<nav class="category-nav">
    <ul class="nav d-flex flex-nowrap overflow-auto" style="scrollbar-width:none;">
        <li class="nav-item"><a class="nav-link active" href="#">Men</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Women</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Kids</a></li>
    </ul>
</nav>
@endsection

@section('content')
<main>

    <!-- Promo banner -->
    <section class="home-banner-section">
        <div class="container">
            <div class="home-banner-scroll" id="home-banner-scroll" aria-label="Promotions">
                <a class="home-banner-card clear-link" href="{{ route('shop') }}">
                    <div class="home-banner-card__media">
                        <img class="img__container" src="{{ asset('images/image_1.jpg') }}" alt="Sneakers collection banner">
                    </div>
                    <div class="home-banner-card__content">
                        <p class="home-banner-card__eyebrow">New Collection</p>
                        <h2>Fresh Sneakers Drop</h2>
                        <p>Discover trending pairs and complete your everyday look.</p>
                    </div>
                </a>

                <a class="home-banner-card clear-link" href="{{ route('shop') }}">
                    <div class="home-banner-card__media">
                        <img class="img__container" src="{{ asset('images/image_2.jpg') }}" alt="Accessories sale banner">
                    </div>
                    <div class="home-banner-card__content">
                        <p class="home-banner-card__eyebrow">Limited Offer</p>
                        <h2>Accessories Sale</h2>
                        <p>Save up to 30% on bags, watches and more.</p>
                    </div>
                </a>

                <a class="home-banner-card clear-link" href="{{ route('shop') }}">
                    <div class="home-banner-card__media">
                        <img class="img__container" src="{{ asset('images/image_3.jpg') }}" alt="Streetwear essentials banner">
                    </div>
                    <div class="home-banner-card__content">
                        <p class="home-banner-card__eyebrow">Daily Picks</p>
                        <h2>Streetwear Essentials</h2>
                        <p>Mix shirts, trousers and layers for your style.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Scroll -->
    <section class="home-section" style="background:#fafafa; border-bottom:1px solid var(--gray-color);">
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
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">footprint</span></div><span class="cat-card__label">Shoes</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">checkroom</span></div><span class="cat-card__label">Shirts</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">styler</span></div><span class="cat-card__label">Trousers</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">watch</span></div><span class="cat-card__label">Accessories</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">backpack</span></div><span class="cat-card__label">Bags</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">diamond</span></div><span class="cat-card__label">Jewelry</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">dry_cleaning</span></div><span class="cat-card__label">Outerwear</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">fitness_center</span></div><span class="cat-card__label">Sportswear</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">footprint</span></div><span class="cat-card__label">Sneakers</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">checkroom</span></div><span class="cat-card__label">T-Shirts</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">styler</span></div><span class="cat-card__label">Jeans</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">watch</span></div><span class="cat-card__label">Watches</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">backpack</span></div><span class="cat-card__label">Backpacks</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">diamond</span></div><span class="cat-card__label">Rings</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">dry_cleaning</span></div><span class="cat-card__label">Coats</span></a>
                        <a class="cat-card clear-link" href="{{ route('search') }}"><div class="cat-card__img"><span class="material-symbols-outlined cat-card__icon" aria-hidden="true">fitness_center</span></div><span class="cat-card__label">Training</span></a>
                    </div>

                    <div class="cat-dots">
                        <button class="cat-dot active" data-page="0"></button>
                        <button class="cat-dot"        data-page="1"></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recommended items -->
    <section class="home-section">
        <div class="container">

            <div class="section-header">
                <h2 class="section-title mb-0">Recommended items</h2>
                <a href="{{ route('shop') }}" class="explore-link">
                    Explore more
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>

            <div class="row g-3 g-md-4">
                <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                    <div class="product-card">
                        <button class="product-card__fav" aria-label="Like"><span class="material-symbols-outlined">favorite</span></button>
                        <a href="{{ route('product') }}" class="clear-link">
                            <div class="product-card__img"><img src="{{ asset('images/image_1.jpg') }}" alt="Product image"></div>
                            <div class="product-card__body">
                                <h3 class="product-card__name">Super View Glasses</h3>
                                <p class="product-card__desc">UV-protective lenses with a lightweight frame. Wide field of view for everyday outdoor wear.</p>
                                <span class="product-card__price">19,99 €</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                    <div class="product-card">
                        <button class="product-card__fav" aria-label="Like"><span class="material-symbols-outlined">favorite</span></button>
                        <a href="{{ route('product') }}" class="clear-link">
                            <div class="product-card__img"><img src="{{ asset('images/image_2.jpg') }}" alt="Product image"></div>
                            <div class="product-card__body">
                                <h3 class="product-card__name">Red Jacket</h3>
                                <p class="product-card__desc">Bold red jacket with a modern slim cut. Water-resistant fabric, perfect for spring and autumn.</p>
                                <span class="product-card__price">104,50 €</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                    <div class="product-card">
                        <button class="product-card__fav" aria-label="Like"><span class="material-symbols-outlined">favorite</span></button>
                        <a href="{{ route('product') }}" class="clear-link">
                            <div class="product-card__img"><img src="{{ asset('images/image_3.jpg') }}" alt="Product image"></div>
                            <div class="product-card__body">
                                <h3 class="product-card__name">White T-shirt</h3>
                                <p class="product-card__desc">Classic 100% cotton tee with a relaxed fit. Soft, breathable and versatile for any casual look.</p>
                                <span class="product-card__price">39,00 €</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                    <div class="product-card">
                        <button class="product-card__fav" aria-label="Like"><span class="material-symbols-outlined">favorite</span></button>
                        <a href="{{ route('product') }}" class="clear-link">
                            <div class="product-card__img"><img src="{{ asset('images/image_1.jpg') }}" alt="Product image"></div>
                            <div class="product-card__body">
                                <h3 class="product-card__name">Super View Glasses</h3>
                                <p class="product-card__desc">Polarised lenses in a sleek frame. Blocks glare and protects eyes on sunny days.</p>
                                <span class="product-card__price">12,99 €</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                    <div class="product-card">
                        <button class="product-card__fav" aria-label="Like"><span class="material-symbols-outlined">favorite</span></button>
                        <a href="{{ route('product') }}" class="clear-link">
                            <div class="product-card__img"><img src="{{ asset('images/image_2.jpg') }}" alt="Product image"></div>
                            <div class="product-card__body">
                                <h3 class="product-card__name">Red Jacket</h3>
                                <p class="product-card__desc">Oversized fit with ribbed cuffs and a zip-up front. Warm lining for extra comfort in cold weather.</p>
                                <span class="product-card__price">89,00 €</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                    <div class="product-card">
                        <button class="product-card__fav" aria-label="Like"><span class="material-symbols-outlined">favorite</span></button>
                        <a href="{{ route('product') }}" class="clear-link">
                            <div class="product-card__img"><img src="{{ asset('images/image_3.jpg') }}" alt="Product image"></div>
                            <div class="product-card__body">
                                <h3 class="product-card__name">White T-shirt</h3>
                                <p class="product-card__desc">Crew-neck essential in premium cotton. Pre-washed fabric that stays soft wash after wash.</p>
                                <span class="product-card__price">24,99 €</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@section('scripts')
<script>
    const catScroll = document.getElementById('cat-scroll');
    const dots = document.querySelectorAll('.cat-dot');
    const prevBtn = document.getElementById('cat-prev');
    const nextBtn = document.getElementById('cat-next');
    const bannerScroll = document.getElementById('home-banner-scroll');

    if (bannerScroll) {
        const banners = bannerScroll.querySelectorAll('.home-banner-card');
        let bannerIndex = 0;
        let bannerTimer = null;

        const goToBanner = (index) => {
            bannerScroll.scrollTo({ left: index * bannerScroll.clientWidth, behavior: 'smooth' });
        };

        const stopBannerAuto = () => { if (bannerTimer) { clearInterval(bannerTimer); bannerTimer = null; } };
        const startBannerAuto = () => {
            if (banners.length < 2) return;
            stopBannerAuto();
            bannerTimer = setInterval(() => { bannerIndex = (bannerIndex + 1) % banners.length; goToBanner(bannerIndex); }, 3600);
        };

        setTimeout(startBannerAuto, 1500);
        bannerScroll.addEventListener('scroll', () => {
            const page = Math.round(bannerScroll.scrollLeft / Math.max(1, bannerScroll.clientWidth));
            bannerIndex = Math.max(0, Math.min(banners.length - 1, page));
        });
        bannerScroll.addEventListener('pointerdown', stopBannerAuto);
        bannerScroll.addEventListener('mouseenter', stopBannerAuto);
        bannerScroll.addEventListener('mouseleave', () => setTimeout(startBannerAuto, 1200));
        window.addEventListener('resize', () => goToBanner(bannerIndex));
    }

    function positionArrows() {
        const scrollTop = catScroll.offsetTop;
        const scrollHeight = catScroll.offsetHeight;
        const arrowH = prevBtn.offsetHeight;
        const centerTop = scrollTop + scrollHeight / 2 - arrowH / 2;
        prevBtn.style.top = centerTop + 'px';
        nextBtn.style.top = centerTop + 'px';
    }

    function updateScrollState() {
        const canScroll = catScroll.scrollWidth > catScroll.clientWidth + 2;
        catScroll.classList.toggle('no-scroll', !canScroll);
        prevBtn.classList.toggle('hidden', !canScroll || catScroll.scrollLeft <= 0);
        nextBtn.classList.toggle('hidden', !canScroll || catScroll.scrollLeft >= catScroll.scrollWidth - catScroll.clientWidth - 2);
        document.querySelector('.cat-dots').style.visibility = canScroll ? 'visible' : 'hidden';
    }

    prevBtn.addEventListener('click', () => { catScroll.scrollBy({ left: -catScroll.offsetWidth * 0.8, behavior: 'smooth' }); });
    nextBtn.addEventListener('click', () => { catScroll.scrollBy({ left:  catScroll.offsetWidth * 0.8, behavior: 'smooth' }); });

    catScroll.addEventListener('scroll', () => {
        const max = catScroll.scrollWidth - catScroll.clientWidth;
        const ratio = max > 0 ? catScroll.scrollLeft / max : 0;
        const pageIndex = ratio < 0.5 ? 0 : 1;
        dots.forEach((d, i) => d.classList.toggle('active', i === pageIndex));
        updateScrollState();
    });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const page = parseInt(dot.dataset.page);
            catScroll.scrollTo({ left: page * (catScroll.scrollWidth - catScroll.clientWidth), behavior: 'smooth' });
        });
    });

    function init() { positionArrows(); updateScrollState(); }
    init();
    window.addEventListener('resize', init);

    document.querySelectorAll('.category-nav .nav-link').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            document.querySelectorAll('.category-nav .nav-link').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });

    document.querySelectorAll('.product-card__fav').forEach(btn => {
        btn.addEventListener('click', () => { btn.classList.toggle('liked'); });
    });
</script>
@endsection
