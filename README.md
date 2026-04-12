# WTech_Eshop — RuPo

E-commerce application built with Laravel 13 + Blade + Bootstrap 5. Supports buyer, seller, and superadmin roles with a full checkout flow and a seller admin panel.

## Requirements

- PHP >= 8.2
- Composer
- PostgreSQL (or any database supported by Laravel)
- Node.js >= 18 + npm (for Vite asset bundling)

## Setup

**1. Clone the repository**
```bash
git clone <repo-url>
cd WTech_Eshop
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```
Open `.env` and set your database connection:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=rupo
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

**4. Run migrations and seed test data**
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

**5. Install JS dependencies and build assets**
```bash
npm install
npm run build
```

## Running the project

```bash
composer run dev
```

Runs `php artisan serve`, `queue:listen` and `npm run dev` concurrently.

The app will be available at [http://localhost:8000](http://localhost:8000).

## Test accounts

All accounts use the password: **`password`**

| Email | Role | Access |
|-------|------|--------|
| `test@example.com` | buyer | Shop, cart, favourites, checkout |
| `admin@example.com` | seller | Admin panel — own brands (Zara, Nike) and their products |
| `superadmin@example.com` | superadmin | Admin panel — all brands and all products |

## User roles

| Role | Description |
|------|-------------|
| `buyer` | Can browse, search, add to cart, favourite, and complete checkout |
| `seller` | Has access to the admin panel; manages their own brands and products |
| `superadmin` | Has full admin access — can view, create, edit, and delete all products and brands across all sellers |

## Useful Artisan commands

| Command | Description |
|---------|-------------|
| `php artisan serve` | Start local development server |
| `php artisan migrate` | Run all pending database migrations |
| `php artisan migrate:fresh` | Drop all tables and re-run migrations from scratch |
| `php artisan migrate:fresh --seed` | Re-run migrations and seed the database with test data |
| `php artisan db:seed` | Run database seeders without dropping tables |
| `php artisan storage:link` | Create the public symlink for uploaded files |
| `php artisan route:list` | List all registered routes |
| `php artisan optimize:clear` | Clear all caches at once |
| `php artisan tinker` | Open an interactive REPL |

## Project structure

```
WTech_Eshop/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── BrandController.php     # Seller/superadmin brand management
│   │   │   │   └── ProductController.php   # Seller/superadmin product CRUD
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   └── RegisteredUserController.php
│   │   │   ├── DeliveryController.php
│   │   │   └── ProfileController.php
│   │   └── Middleware/
│   │       └── SellerMiddleware.php         # Allows seller + superadmin only
│   └── Models/
│       ├── User.php
│       ├── Brand.php          # Belongs to a seller (user_id)
│       ├── Category.php       # Hierarchical (parent_id)
│       ├── Product.php        # UUID PK, belongs to brand + category
│       ├── ProductVariant.php # Price + size symbol + inventory
│       ├── Image.php          # UUID PK, used by products and banners
│       ├── Banner.php         # Homepage promo banners
│       ├── Favourite.php      # Pivot: user ↔ product
│       ├── Cart.php           # UUID PK, guest (session) or authenticated
│       ├── CartItem.php       # Belongs to cart + product variant
│       ├── DeliveryMethod.php # Slovenská pošta, Packeta, GLS, DHL
│       ├── DeliveryInformation.php
│       └── Order.php
│
├── database/
│   ├── migrations/            # 15 migrations (users → orders)
│   ├── factories/
│   │   └── UserFactory.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── BrandSeeder.php         # Zara/Nike assigned to seller; others unowned
│       ├── CategorySeeder.php      # 16 categories
│       ├── BannerSeeder.php        # 3 homepage banners
│       ├── DeliveryMethodSeeder.php
│       └── ProductSeeder.php       # 6 products with variants and images
│
├── public/
│   ├── css/                   # One stylesheet per page + global main.css
│   │   ├── main.css           # CSS variables, navbar, footer, shared components
│   │   ├── index.css          # Home page
│   │   ├── main_page.css      # Shop / product listing
│   │   ├── product.css        # Product detail
│   │   ├── search.css         # Search & filters
│   │   ├── cart.css           # Cart + checkout steps bar
│   │   ├── delivery.css       # Delivery form
│   │   ├── payment.css        # Payment methods
│   │   ├── order-success.css
│   │   ├── favourites.css
│   │   ├── profile.css
│   │   ├── auth.css           # Login & registration
│   │   ├── adproduct.css      # Admin product list
│   │   └── addingproduct.css  # Admin add/edit product form
│   ├── images/                # Static placeholder images
│   └── storage -> storage/app/public   # Symlink for uploaded files
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php       # Public layout: navbar, footer
│       │   ├── admin.blade.php     # Admin layout: Products/Brands nav
│       │   └── auth.blade.php      # Auth layout: no navbar
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── admin/
│       │   ├── products.blade.php      # Product table; brand filter for superadmin
│       │   ├── brands.blade.php        # Brand list; seller column for superadmin
│       │   ├── add-product.blade.php   # Add product form with image upload
│       │   └── edit-product.blade.php  # Edit product form pre-filled
│       ├── index.blade.php         # Home: banners + categories + recommended
│       ├── shop.blade.php          # Product grid with search bar
│       ├── product.blade.php       # Product detail: carousel, sizes, add to cart
│       ├── search.blade.php        # Search results with sidebar filters
│       ├── cart.blade.php          # Cart items + price summary
│       ├── favourites.blade.php    # Liked products grid
│       ├── profile.blade.php       # User profile with edit mode
│       ├── delivery.blade.php      # Delivery service selection + address form
│       ├── payment.blade.php       # Payment methods + card form
│       └── order-success.blade.php # Order confirmation page
│
├── routes/
│   ├── web.php             # All application routes
│   └── auth.php            # Login / register / logout routes
│
├── bootstrap/
│   └── app.php             # Middleware aliases (seller)
│
├── .env.example
├── composer.json
├── package.json
└── vite.config.js
```

## Database schema

| Table | PK | Description |
|-------|----|-------------|
| `users` | bigint | `user_type`: buyer / seller / superadmin |
| `brands` | bigint | `user_id` FK → seller who owns this brand |
| `categories` | bigint | `parent_id` for hierarchy |
| `images` | UUID | Shared by products and banners |
| `banners` | UUID | Homepage promo cards |
| `products` | UUID | `brand_id`, `category_id`, `sex`, `status` |
| `product_images` | composite | Pivot: product ↔ image |
| `product_variants` | UUID | `symbol` (XS–XXL), `price`, `inventory` |
| `favourites` | composite | Pivot: user ↔ product |
| `carts` | UUID | `session_id` (guest) or `user_id` (auth) |
| `cart_items` | UUID | `variant_id`, `quantity`, `amount` (price snapshot) |
| `delivery_methods` | UUID | Slovenská pošta, Packeta, GLS, DHL |
| `delivery_information` | UUID | Shipping address |
| `orders` | UUID | Links user, cart, delivery method and address |

## CSS design system (`public/css/main.css`)

| Variable | Value | Usage |
|----------|-------|-------|
| `--bg-primary-color` | `#FFA883` | Primary orange/salmon accent |
| `--highlight-color` | `#B5BAFF` | CTA buttons, active states |
| `--highlight-dark-color` | `#9ca1e5` | Hover state for CTA |
| `--gray-color` | `#D9D9D9` | Borders, backgrounds |
| `--dark-gray-color` | `#7b7b7b` | Secondary text |
| `--input-form` | `#FFDEDE` | Form input backgrounds |

Font: **Anaheim** (Google Fonts). Auth pages additionally use **Libre Barcode 39 Text** for the logo.
Responsive breakpoints: mobile `< 480px`, tablet `480–760px`, desktop `760–1200px`, large `1200px+`.

## Layouts & Blade slots

| Layout | Used by | Yields |
|--------|---------|--------|
| `layouts/app` | All public pages | `title`, `extra-css`, `subnav`, `content`, `scripts` |
| `layouts/admin` | Admin pages | `title`, `extra-css`, `content`, `scripts` |
| `layouts/auth` | Login, Register | `title`, `content` |
