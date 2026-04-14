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

    <title>@yield('title', 'Admin — RuPo')</title>

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adproduct.css') }}">
    @yield('extra-css')
</head>
<body>

<!-- Admin Header -->
<nav class="navbar">
    <div class="container-fluid d-flex align-items-center gap-3">
        <a class="navbar-brand flex-shrink-0" href="{{ route('home') }}">RuPo</a>

        <div class="d-flex gap-1 flex-shrink-0">
            <a href="{{ route('admin.products') }}"
               class="admin-nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                Products
            </a>
            <a href="{{ route('admin.brands') }}"
               class="admin-nav-link {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
                Brands
            </a>
        </div>

        <div class="admin-search-wrap flex-grow-1">
            <input class="admin-search" type="search" placeholder="Search">
        </div>

        <span class="admin-seller-name d-none d-md-block flex-shrink-0">
            {{ auth()->user()->name }}
        </span>
        <a class="header__icon flex-shrink-0" href="{{ route('profile') }}" title="Profile">
            <span class="material-symbols-outlined">account_circle</span>
        </a>
    </div>
</nav>

<style>
.admin-nav-link {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: clamp(0.8rem, 0.95vw, 1rem);
    color: #000;
    text-decoration: none;
    transition: background-color 0.2s;
}
.admin-nav-link:hover { background-color: var(--gray-color); color: #000; }
.admin-nav-link.active { background-color: var(--gray-color); font-weight: 600; }
.admin-seller-name { font-size: clamp(0.8rem, 0.9vw, 1rem); color: var(--dark-gray-color); }
</style>

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
