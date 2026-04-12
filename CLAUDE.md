# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

WTech_Eshop is a static e-commerce frontend prototype — no build system, no package manager, no backend. All dependencies (Bootstrap 5.3.3, Google Fonts, Material Symbols) are loaded via CDN.

## Running the Project

Open any `.html` file directly in a browser. No server or build step required.

## Architecture

### Structure
- `html/` — 11 pages covering the full e-commerce flow
- `styles/` — one CSS file per HTML page, plus `main.css` for global styles

### CSS Design System (`styles/main.css`)
All global CSS variables are defined at the top of `main.css`:
- `--bg-primary-color: #FFA883` (primary orange/salmon)
- `--highlight-color: #B5BAFF` / `--highlight-dark-color: #9ca1e5` (CTA purple)
- `--gray-color: #D9D9D9`, `--dark-gray-color: #7b7b7b`
- `--input-form: #FFDEDE` (form input backgrounds)
- `--font-base: clamp(14px, 1vw, 20px)` (responsive base font)
- Font: "Anaheim" (Google Fonts)

Responsive breakpoints: mobile < 480px, tablet 480–760px, desktop 760–1200px, large 1200px+. Uses `clamp()` extensively for fluid sizing and `max-width: min(1800px, 95vw)` for page containers.

### Page–Style Pairing
Each page has a dedicated stylesheet with the same base name:
- `index-bootstrap.html` ↔ `index.css`
- `main_page.html` ↔ `main_page.css`
- `product-bootstrap.html` ↔ `product.css`
- `cart-bootstrap.html` ↔ `cart.css`
- `search-bootstrap.html` ↔ `search.css`
- `favourites-bootstrap.html` ↔ `favourites.css`
- `delivery_bootstrap.html` ↔ `delivery.css`
- `payment_bootstrap.html` ↔ `payment.css`
- `profile_bootstrap.html` ↔ `profile.css`
- `login-bootstrap.html` / `registration-bootstrap.html` ↔ `auth.css`

### JavaScript
All JS is inline `<script>` blocks within the HTML files — no separate JS files. Key implementations:
- **Cart** (`cart-bootstrap.html`): quantity increment/decrement, item deletion with fade-out, real-time price recalculation using `data-price` attributes
- **Index** (`index-bootstrap.html`): category scroll carousel with arrow nav and pagination dots, favorites toggle
- **Product** (`product-bootstrap.html`): Bootstrap Carousel for image gallery with touch/swipe support

### Product Cards
Product cards (`.product-card`) appear in `index-bootstrap.html`, `main_page.html`, `search-bootstrap.html`, and `favourites-bootstrap.html`. Each card wraps `product-card__img` and `product-card__body` in `<a href="./product-bootstrap.html" class="clear-link">`. The fav/delete button sits **outside** the `<a>` tag to avoid navigation conflict on click.

### User Flow
Home → Shop/Search → Product Detail → Cart → Delivery → Payment (linear checkout). Profile, Favorites, Login, and Registration are accessible via the navbar.

### Navbar
Shared navbar structure appears in all pages: logo (links to `./index-bootstrap.html`), favorites icon, cart icon, profile icon. Styles come from `main.css`. The `.cart-summary__btn` class is used on both `<button>` and `<a>` elements across cart/delivery/payment pages.
