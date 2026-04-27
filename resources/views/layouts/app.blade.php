<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts & Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,400,0,0"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anaheim:wght@400..800&display=swap" rel="stylesheet">

    <title>@yield('title', 'RuPo')</title>

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @yield('extra-css')
</head>
<body data-auth="{{ auth()->check() ? '1' : '0' }}" data-login-url="{{ route('login') }}" data-favourite-url="{{ route('favourites.toggle') }}">

<!-- Header -->
<nav class="navbar">
    <div class="container-fluid d-flex align-items-center gap-3">
        <a class="navbar-brand flex-shrink-0" href="{{ route('home') }}">RuPo</a>

        @unless(request()->routeIs('profile', 'search'))
            <form class="navbar-search-wrap" method="GET" action="{{ route('search') }}">
                <input class="navbar-search" type="search" name="q" value="{{ request('q') }}" placeholder="Search" aria-label="Search products">
            </form>
        @endunless

        <div class="d-flex align-items-center gap-1 flex-shrink-0 ms-auto">
            <a class="header__icon" href="{{ route('favourites') }}" title="Liked">
                <span class="material-symbols-outlined">favorite</span>
            </a>
            <a class="header__icon" href="{{ route('cart') }}" title="Cart">
                <span class="material-symbols-outlined">shopping_bag</span>
            </a>
            <a class="header__icon" href="{{ auth()->check() ? route('profile') : route('login') }}" title="Profile">
                <span class="material-symbols-outlined">account_circle</span>
            </a>
        </div>
    </div>
</nav>

@yield('subnav')

@yield('content')

<!-- Footer -->
<footer class="py-3 px-4">
    <div class="container-fluid d-flex flex-column flex-sm-row
                justify-content-between align-items-center gap-2">
        <small>© 2026 Rusaniuk&amp;Popovych, Inc. All rights reserved</small>
        <div class="d-flex flex-wrap justify-content-center gap-1">
            <a href="#">Get Help</a>
            <a href="#">About Us</a>
            <a href="#">Contacts</a>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('click', async event => {
        const toggleBtn = event.target.closest('[data-favourite-toggle]');
        if (!toggleBtn) return;

        const body = document.body;
        const isAuthed = body.dataset.auth === '1';
        if (!isAuthed) {
            window.location.href = body.dataset.loginUrl || '/login';
            return;
        }

        const productId = toggleBtn.dataset.productId;
        const toggleUrl = body.dataset.favouriteUrl;
        if (!productId || !toggleUrl) return;

        try {
            const response = await fetch(toggleUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ product_id: productId }),
            });

            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error('Unable to update favourites');
            }

            const isFavourited = Boolean(data.favourited);
            toggleBtn.dataset.favourited = isFavourited ? '1' : '0';
            toggleBtn.classList.toggle('liked', isFavourited);

            const icon = toggleBtn.querySelector('.material-symbols-outlined');
            if (icon) {
                icon.textContent = isFavourited ? 'favorite' : 'favorite_border';
            }

            toggleBtn.setAttribute(
                'aria-label',
                isFavourited ? 'Remove from favourites' : 'Add to favourites'
            );
            if (toggleBtn.title !== undefined) {
                toggleBtn.title = isFavourited ? 'Remove from favourites' : 'Add to favourites';
            }
        } catch (error) {
            console.error(error);
        }
    });
</script>
@yield('scripts')
</body>
</html>
