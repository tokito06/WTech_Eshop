# Plan: Full-Stack Conversion of WTech_Eshop

## Context

WTech_Eshop — статичний прототип e-commerce на Laravel 13 + Blade + Bootstrap 5. Автентифікація (login/register/profile) вже працює через Laravel Breeze. Всі інші дані (товари, кошик, замовлення, обране) — захардкоджені в Blade-шаблонах. Потрібно додати моделі, міграції, контролери та оновити шаблони для роботи з реальними даними з БД.

---

## Phase 1: Database — Models & Migrations

### 1.1 Створити міграції та моделі

| Model | Table | Key Fields |
|-------|-------|------------|
| `Category` | `categories` | id, name, slug, icon (material symbol name) |
| `Brand` | `brands` | id, name, slug |
| `Product` | `products` | id, name, description, price, category_id (FK), brand_id (FK), sex (enum: men/women/kids/unisex), type, vendor, status (active/archived), created_at |
| `ProductImage` | `product_images` | id, product_id (FK), image_path, sort_order |
| `ProductSize` | `product_sizes` | id, product_id (FK), size (enum: XS/S/M/L/XL/XXL), inventory (int) |
| `Favourite` | `favourites` | id, user_id (FK), product_id (FK), unique(user_id, product_id) |
| `Cart` | `carts` | id, user_id (FK, unique) |
| `CartItem` | `cart_items` | id, cart_id (FK), product_id (FK), size, quantity |
| `Order` | `orders` | id, user_id (FK), order_number, status (pending/processing/shipped/delivered), delivery_service, delivery_price, items_total, total, created_at |
| `OrderItem` | `order_items` | id, order_id (FK), product_id (FK), product_name, price, size, quantity |
| `DeliveryAddress` | `delivery_addresses` | id, order_id (FK), name, surname, email, phone, street, city, zip, country |

**Files to create:**
- `database/migrations/xxxx_create_categories_table.php`
- `database/migrations/xxxx_create_brands_table.php`
- `database/migrations/xxxx_create_products_table.php`
- `database/migrations/xxxx_create_product_images_table.php`
- `database/migrations/xxxx_create_product_sizes_table.php`
- `database/migrations/xxxx_create_favourites_table.php`
- `database/migrations/xxxx_create_carts_table.php`
- `database/migrations/xxxx_create_cart_items_table.php`
- `database/migrations/xxxx_create_orders_table.php`
- `database/migrations/xxxx_create_order_items_table.php`
- `database/migrations/xxxx_create_delivery_addresses_table.php`

**Models to create** (in `app/Models/`):
- `Category.php`, `Brand.php`, `Product.php`, `ProductImage.php`, `ProductSize.php`
- `Favourite.php`, `Cart.php`, `CartItem.php`
- `Order.php`, `OrderItem.php`, `DeliveryAddress.php`

**Update existing:** `app/Models/User.php` — додати relationships (hasOne cart, hasMany orders, hasMany favourites)

### 1.2 Seeders

- `database/seeders/CategorySeeder.php` — 16 категорій з поточного UI
- `database/seeders/BrandSeeder.php` — Nike, Adidas, Puma, New Balance, Zara
- `database/seeders/ProductSeeder.php` — мін. 20-30 товарів з зображеннями та розмірами
- Оновити `DatabaseSeeder.php`

---

## Phase 2: Controllers & Routes

### 2.1 Публічні (без auth)

| Controller | Routes | Дія |
|------------|--------|-----|
| `HomeController` | `GET /` | Головна: категорії з БД, рекомендовані товари |
| `ShopController` | `GET /shop` | Каталог товарів з пагінацією |
| `ProductController` | `GET /product/{product}` | Деталі товару з зображеннями, розмірами |
| `SearchController` | `GET /search` | Пошук + фільтри (category, brand, price, size, sort) з query params |

### 2.2 Authenticated

| Controller | Routes | Дія |
|------------|--------|-----|
| `CartController` | `GET /cart` | Показати кошик |
| | `POST /cart/add` | Додати товар (product_id, size, qty) |
| | `PATCH /cart/{cartItem}` | Оновити кількість |
| | `DELETE /cart/{cartItem}` | Видалити з кошика |
| `FavouriteController` | `GET /favourites` | Список обраного |
| | `POST /favourites/toggle` | Додати/видалити з обраного |
| `CheckoutController` | `GET /delivery` | Форма доставки |
| | `POST /delivery` | Зберегти адресу, перейти до оплати |
| | `GET /payment` | Сторінка оплати |
| | `POST /payment` | Створити замовлення, очистити кошик |
| | `GET /order-success/{order}` | Підтвердження замовлення |

### 2.3 Admin

| Controller | Routes | Дія |
|------------|--------|-----|
| `Admin\ProductController` | `GET /admin/products` | Список товарів з фільтрацією |
| | `GET /admin/products/create` | Форма створення |
| | `POST /admin/products` | Зберегти новий товар |
| | `GET /admin/products/{product}/edit` | Форма редагування |
| | `PUT /admin/products/{product}` | Оновити товар |
| | `DELETE /admin/products/{product}` | Видалити товар |

**Files to create/modify:**
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/ShopController.php`
- `app/Http/Controllers/ProductController.php`
- `app/Http/Controllers/SearchController.php`
- `app/Http/Controllers/CartController.php`
- `app/Http/Controllers/FavouriteController.php`
- `app/Http/Controllers/CheckoutController.php`
- `app/Http/Controllers/Admin/ProductController.php`
- **Update:** `routes/web.php` — замінити `Route::view()` на controller routes

---

## Phase 3: Blade Templates — Data Binding

Замінити захардкоджений контент на Blade-директиви з даними з контролерів.

### 3.1 Layout (`resources/views/layouts/app.blade.php`)
- Показувати кількість товарів у кошику в навбарі (badge на іконці cart)
- Показувати кількість обраних (badge на іконці heart)

### 3.2 Home (`resources/views/index.blade.php`)
- `@foreach($categories as $category)` — динамічні категорії
- `@foreach($recommended as $product)` — рекомендовані з БД
- Favourite toggle: перевірка `$product->isFavouritedBy(auth()->user())`

### 3.3 Shop (`resources/views/shop.blade.php`)
- `@foreach($products as $product)` з пагінацією
- Динамічні розміри з `$product->sizes`

### 3.4 Product (`resources/views/product.blade.php`)
- Всі поля з `$product`
- `@foreach($product->images as $image)` для каруселі
- Доступні розміри з `$product->sizes->where('inventory', '>', 0)`
- Форма "Add to bag" → `POST /cart/add`

### 3.5 Search (`resources/views/search.blade.php`)
- Фільтри з query params: `?q=&category=&brand=&min_price=&max_price=&size=&sort=`
- `@foreach($categories as $cat)` в sidebar
- `@foreach($brands as $brand)` в sidebar
- `@foreach($products as $product)` з пагінацією
- Зберігати стан фільтрів через `old()` або request values

### 3.6 Cart (`resources/views/cart.blade.php`)
- `@foreach($cart->items as $item)` — реальні товари
- Quantity +/− → `PATCH /cart/{cartItem}` (AJAX або form)
- Delete → `DELETE /cart/{cartItem}`
- Динамічний підрахунок totals

### 3.7 Delivery (`resources/views/delivery.blade.php`)
- Pre-fill форму з `auth()->user()` (name, surname, email, phone)
- `POST /delivery` зберігає всі поля в session для checkout

### 3.8 Payment (`resources/views/payment.blade.php`)
- Показувати реальний total з кошика + delivery price
- `POST /payment` → створити Order + OrderItems + DeliveryAddress, очистити Cart

### 3.9 Order Success (`resources/views/order-success.blade.php`)
- Дані з `$order`: номер, items total, delivery, grand total

### 3.10 Favourites (`resources/views/favourites.blade.php`)
- `@foreach($favourites as $favourite)` — реальні товари
- Delete → `POST /favourites/toggle`

### 3.11 Profile (`resources/views/profile.blade.php`)
- Додати секцію "Order History" з `$orders`

### 3.12 Admin pages
- `admin/products.blade.php` — `@foreach($products as $product)` реальна таблиця
- `admin/add-product.blade.php` — `POST /admin/products` з file upload
- `admin/edit-product.blade.php` — `PUT /admin/products/{product}` з pre-filled data

---

## Phase 4: Image Upload & Storage

- Налаштувати `storage/app/public/products/` для зображень товарів
- `php artisan storage:link` для public доступу
- В admin формах: обробка file upload через `$request->file('images')`
- Multiple images upload для одного товару
- Thumbnail generation (опціонально, можна resize через CSS)

---

## Phase 5: Interactive Features (AJAX)

Для кращого UX замінити повні перезавантаження на AJAX запити:

- **Cart +/−/delete** → fetch API з оновленням DOM
- **Favourite toggle** → fetch API (heart icon toggle)
- **Search filters** → fetch API з оновленням product grid (або залишити form submit)

Це робиться через inline `<script>` з `fetch()` (зберігаючи патерн проєкту).

---

## Phase 6: Middleware & Authorization

- Створити middleware `AdminMiddleware` — перевірка `user_type === 'seller'`
- Застосувати до admin routes
- Захистити cart/favourites/checkout routes через `auth` middleware (вже є)
- Додати `@auth`/`@guest` в navbar для показу/приховування іконок login vs profile

---

## Phase 7: Validation

Додати Form Request classes:

- `StoreProductRequest` — для admin створення товару
- `UpdateProductRequest` — для admin редагування
- `AddToCartRequest` — product_id, size, quantity
- `StoreDeliveryRequest` — всі поля адреси
- `StorePaymentRequest` — базова валідація (реальний payment gateway — окремий етап)

---

## Order of Implementation

1. **Phase 1** — Migrations + Models + Seeders (фундамент)
2. **Phase 6** — Middleware (потрібен до контролерів)
3. **Phase 2** — Controllers + Routes
4. **Phase 3** — Blade template updates
5. **Phase 4** — Image upload
6. **Phase 7** — Validation
7. **Phase 5** — AJAX improvements (polish)

---

## Verification

1. `php artisan migrate:fresh --seed` — БД створюється без помилок
2. Відкрити `/` — категорії та товари з БД
3. Перейти `/shop` — товари з пагінацією
4. Відкрити `/product/{id}` — деталі, зображення, розміри
5. `/search?q=jacket` — пошук працює, фільтри фільтрують
6. Зареєструватися → додати в кошик → checkout flow до order-success
7. Додати/видалити з обраного
8. Admin: CRUD товарів з upload зображень
9. Перевірити responsive на мобільних breakpoints
