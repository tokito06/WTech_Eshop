<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
<body>

<!-- Header -->
<nav class="navbar">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="{{ route('home') }}">RuPo</a>
        <div class="d-flex align-items-center gap-1">
            <a class="header__icon" href="{{ route('favourites') }}" title="Liked">
                <span class="material-symbols-outlined">favorite</span>
            </a>
            <a class="header__icon" href="{{ route('cart') }}" title="Cart">
                <span class="material-symbols-outlined">shopping_bag</span>
            </a>
            <a class="header__icon" href="{{ route('profile') }}" title="Profile">
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
@yield('scripts')
</body>
</html>
