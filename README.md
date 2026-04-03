# WTech_Eshop — RuPo

E-commerce frontend built with Laravel (Blade templates) + Bootstrap 5.

## Requirements

- PHP >= 8.1
- Composer
- Node.js >= 18 + npm (optional, for Vite asset bundling)

## Setup

**1. Clone the repository**
```bash
git clone <repo-url>
cd WTech_Eshop
```

**2. Run setup script** (installs dependencies, creates `.env`, generates key, runs migrations, builds assets)
```bash
composer setup
```

> **Note:** Before running the setup script, optionally open `.env` and configure your database connection (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) to match your local environment.

## Running the project

```bash
composer run dev
```

Runs `php artisan serve`, `queue:listen` and `npm run dev` concurrently.

The app will be available at [http://localhost:8000](http://localhost:8000).

## Useful Artisan commands

| Command | Description |
|---------|-------------|
| `php artisan serve` | Start local development server |
| `php artisan migrate` | Run all pending database migrations |
| `php artisan migrate:fresh` | Drop all tables and re-run migrations from scratch |
| `php artisan migrate:fresh --seed` | Re-run migrations and seed the database with test data |
| `php artisan db:seed` | Run database seeders |
| `php artisan make:model ModelName -m` | Create a model with a migration file |
| `php artisan make:controller ControllerName` | Create a new controller |
| `php artisan make:migration create_table_name` | Create a new migration file |
| `php artisan make:seeder SeederName` | Create a new seeder |
| `php artisan route:list` | List all registered routes |
| `php artisan config:clear` | Clear the configuration cache |
| `php artisan cache:clear` | Clear the application cache |
| `php artisan view:clear` | Clear compiled Blade templates |
| `php artisan optimize:clear` | Clear all caches at once |
| `php artisan tinker` | Open an interactive REPL to interact with the app |

## Project structure

```
WTech_Eshop/
│
├── app/
│   ├── Http/
│   │   └── Controllers/    # Laravel controllers (to be added)
│   ├── Models/             # Eloquent models (to be added)
│   └── Providers/
│
├── database/
│   ├── migrations/         # Database table definitions
│   ├── factories/          # Model factories for testing
│   └── seeders/            # Database seeders
│
├── public/
│   ├── css/                # Stylesheets (one per page + main.css)
│   │   ├── main.css        # Global styles, CSS variables, navbar, footer
│   │   ├── index.css       # Home page
│   │   ├── main_page.css   # Shop/product listing page
│   │   ├── product.css     # Product detail page
│   │   ├── search.css      # Search & filters page
│   │   ├── cart.css        # Cart + checkout steps bar
│   │   ├── delivery.css    # Delivery form
│   │   ├── payment.css     # Payment methods
│   │   ├── order-success.css
│   │   ├── favourites.css
│   │   ├── profile.css
│   │   ├── auth.css        # Login & registration
│   │   ├── adproduct.css   # Admin product list
│   │   └── addingproduct.css # Admin add/edit product form
│   └── images/             # Static product images
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php       # Main layout: header, footer, @yield slots
│       │   ├── admin.blade.php     # Admin layout: admin navbar
│       │   └── auth.blade.php      # Auth layout: no navbar
│       │
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       │
│       ├── admin/
│       │   ├── products.blade.php      # Product table with filters
│       │   ├── add-product.blade.php   # Add product form
│       │   └── edit-product.blade.php  # Edit product form
│       │
│       ├── index.blade.php         # Home: promo banners + categories + recommended
│       ├── shop.blade.php          # Product grid with search bar
│       ├── product.blade.php       # Product detail: carousel, size, qty, add to bag
│       ├── search.blade.php        # Search results with sidebar filters
│       ├── cart.blade.php          # Cart items + price summary
│       ├── favourites.blade.php    # Liked products grid
│       ├── profile.blade.php       # User info with edit mode
│       ├── delivery.blade.php      # Delivery service selection + address form
│       ├── payment.blade.php       # Payment methods + card form
│       └── order-success.blade.php # Order confirmation
│
├── routes/
│   └── web.php             # All application routes
│
├── .env.example            # Environment variable template
├── composer.json           # PHP dependencies + dev scripts
├── package.json            # JS dependencies (Vite)
└── vite.config.js
```

### CSS design system (`public/css/main.css`)

All global CSS variables:

| Variable | Value | Usage |
|----------|-------|-------|
| `--bg-primary-color` | `#FFA883` | Primary orange/salmon |
| `--highlight-color` | `#B5BAFF` | CTA buttons, accents |
| `--highlight-dark-color` | `#9ca1e5` | Hover state for CTA |
| `--gray-color` | `#D9D9D9` | Borders, dividers |
| `--dark-gray-color` | `#7b7b7b` | Secondary text |
| `--input-form` | `#FFDEDE` | Form input backgrounds |

Font: **Anaheim** (Google Fonts). Auth pages additionally use **Libre Barcode 39 Text** for the logo.

### Layouts & Blade slots

| Layout | Used by | Yields |
|--------|---------|--------|
| `layouts/app` | All public pages | `title`, `extra-css`, `subnav`, `content`, `scripts` |
| `layouts/admin` | Admin pages | `title`, `extra-css`, `content`, `scripts` |
| `layouts/auth` | Login, Register | `title`, `content`, `scripts` |

The `subnav` slot is used for the category nav bar (home, shop, search), checkout steps bar (cart, delivery, payment), and breadcrumb (product detail).
