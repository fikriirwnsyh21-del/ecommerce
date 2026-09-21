# Rencana Implementasi Website E-Commerce Marketplace Laravel 13

Pembuatan website E-Commerce Marketplace modern dengan arsitektur bersih, responsive, terinspirasi dari pengalaman belanja modern seperti Tokopedia (palet warna hijau modern `#00AA5B`, kartu produk bersudut lengkung halus, navigasi dinamis, filter komprehensif) menggunakan **Laravel 13**, **PHP 8.3+**, **MySQL 8+**, **Tailwind CSS**, **Alpine.js**, dan **Laravel Sanctum**.

---

## User Review Required

> [!IMPORTANT]
> Proyek akan diinisialisasi langsung di direktori kerja `d:\ecommerce` menggunakan **Laravel 13**. Daemon MySQL 8.0.30 lokal (Laragon) telah aktif dan database `ecommerce` telah disiapkan.
>
> Sistem mengadopsi struktur multi-vendor marketplace dengan 3 peran utama (**Customer**, **Seller**, **Admin**). Seluruh proses checkout dilindungi **Database Transaction** dan **Pessimistic Locking (`lockForUpdate`)** untuk mencegah *negative stock* dan *race condition*.

---

## Arsitektur & Struktur Proyek

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/         (Dashboard, User, Category, Product, Order, Marketing)
│   │   ├── Seller/        (Dashboard, Shop, Product, Order, Report)
│   │   ├── Customer/      (Profile, Address, Order, Wishlist)
│   │   ├── Api/           (Auth, Product, Category, Cart, Checkout, Order)
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── ShopController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   └── ReviewController.php
│   ├── Requests/          (Form Requests untuk validasi kuat)
│   └── Resources/         (Sanctum API Resources konsisten)
├── Models/                (User, Role, Category, Shop, Product, Order, dll.)
├── Services/              (CartService, CheckoutService, OrderService, PaymentService, VoucherService)
├── Policies/              (ProductPolicy, OrderPolicy, ShopPolicy)
└── Notifications/         (OrderPlacedNotification, OrderShippedNotification, LowStockNotification)
resources/
├── views/
│   ├── layouts/           (app, admin, seller, auth)
│   ├── components/        (product-card, badge, modal, toast, rating, input)
│   ├── home.blade.php
│   ├── products/          (index, show)
│   ├── shops/             (show)
│   ├── cart/              (index)
│   ├── checkout/          (index, success)
│   ├── orders/            (index, show)
│   ├── seller/            (dashboard, products, orders, settings)
│   └── admin/             (dashboard, users, categories, products, orders, banners)
```

---

## Proposed Changes

### FASE 1: Foundation, Auth & Database Architecture
Inisialisasi kerangka kerja Laravel 13, konfigurasi database MySQL, migrasi tabel lengkap, relasi model Eloquent, seeder dan factory lengkap.

#### [NEW] Database Migrations
- `create_roles_table.php` (id, name, slug)
- `create_users_table.php` (id, role_id, name, email, phone, avatar, password, is_active)
- `create_categories_table.php` (id, parent_id, name, slug, icon, image, is_active)
- `create_shops_table.php` (id, user_id, name, slug, description, logo, banner, city, address, rating, is_active)
- `create_products_table.php` (id, shop_id, category_id, name, slug, description, price, discount_price, stock, sku, weight, condition, is_active, views_count, sales_count)
- `create_product_images_table.php` (id, product_id, image_path, is_primary, sort_order)
- `create_product_variants_table.php` (id, product_id, name, value, price_adjustment, stock, sku)
- `create_addresses_table.php` (id, user_id, recipient_name, phone, province, city, district, postal_code, address_line, is_primary)
- `create_carts_table.php` & `create_cart_items_table.php` (id, cart_id, product_id, variant_id, quantity)
- `create_orders_table.php` (id, user_id, order_number, subtotal, shipping_cost, discount_amount, grand_total, status, notes)
- `create_order_items_table.php` (id, order_id, product_id, shop_id, variant_id, product_name, price, quantity, subtotal)
- `create_payments_table.php` (id, order_id, payment_number, method, channel, status, amount, paid_at)
- `create_reviews_table.php` (id, order_item_id, user_id, product_id, rating, comment, photo_path)
- `create_vouchers_table.php` & `create_voucher_usages_table.php` (code, type, amount, min_purchase, max_discount, quota, start_date, end_date, is_active)
- `create_flash_sales_table.php` (id, product_id, flash_price, stock_quota, sold_count, start_time, end_time, is_active)
- `create_banners_table.php` (id, title, image_path, target_url, position, is_active, sort_order)
- `create_wishlists_table.php` (id, user_id, product_id)

#### [NEW] Eloquent Models
- Relasi lengkap dengan type hinting & eager loading scopes: `User`, `Role`, `Category`, `Shop`, `Product`, `ProductImage`, `ProductVariant`, `Address`, `Cart`, `CartItem`, `Order`, `OrderItem`, `Payment`, `Review`, `Voucher`, `FlashSale`, `Banner`, `Wishlist`.

#### [NEW] Factories & Seeders
- `DatabaseSeeder.php` yang mengisi data realistis:
  - Admin: `admin@example.com` / `password`
  - Seller: `seller@example.com` / `password` (dengan toko aktif)
  - Customer: `customer@example.com` / `password`
  - 10 Kategori populer (Elektronik, Fashion Pria, Fashion Wanita, Komputer, Handphone, Gaming, Rumah Tangga, Kesehatan, Kecantikan, Otomotif)
  - 5 Toko Seller dengan lokasi kota di Indonesia
  - 20+ Produk lengkap variasi, foto ilustrasi, rating, diskon, stok
  - Banner promosi, Flash sale aktif dengan timer, Voucher belanja

#### [NEW] Authentication & Role Middleware
- Auth Controller: Login, Register, Logout, Password Reset.
- Middleware: `RoleMiddleware` (`auth.role:admin`, `auth.role:seller`, `auth.role:customer`).

---

### FASE 2: Customer Marketplace, Product Catalog & Shop Profile

#### [NEW] Layout & Reusable Blade Components
- `layouts/app.blade.php`: Header sticky Tokopedia-style, logo brand marketplace modern, search bar dengan kategori & auto-complete Alpine.js, ikon Wishlist, Cart counter badge, dropdown profil & auth, mobile bottom navigation bar, footer informatif.
- `components/product-card.blade.php`: Foto produk, badge diskon %, judul produk (2-line clamp), rating bintang, jumlah terjual, format harga Rupiah tebal, harga coret, nama & kota toko.
- `components/toast.blade.php`: Alpine.js floating toast notification untuk respon aksi (tambah cart, wishlist, dll).

#### [NEW] Views & Controllers
- `HomeController.php` & `home.blade.php`:
  - Hero banner carousel responsif
  - Quick Category navigation
  - Flash Sale section dengan countdown timer Alpine.js live
  - Produk Terlaris & Rekomendasi
- `ProductController.php` & `products/index.blade.php`:
  - Filter multi-kriteria: Kategori, rentang harga, rating bintang, kota/lokasi, status stok
  - Pengurutan: Harga terendah/tertinggi, terbaru, terlaris/popularitas
  - Search MySQL fulltext / like search
  - Pagination responsif (Grid 2 kolom di mobile, 3 di tablet, 4-5 di desktop)
- `products/show.blade.php`:
  - Gallery gambar produk interaktif dengan thumbnail zoom/click switcher
  - Selektor varian produk yang mengupdate harga & stok dinamis
  - Kartu profil toko penjual (logo, nama, rating, tombol kunjungi toko)
  - Tombol Tambah ke Keranjang, Beli Sekarang, dan Toggle Wishlist
  - Daftar review pembeli dengan rating bintang & foto ulasan
- `ShopController.php` & `shops/show.blade.php`:
  - Banner toko, logo toko, deskripsi, lokasi, statistik toko, dan katalog produk toko

---

### FASE 3: Cart, Wishlist, Address & Checkout Pipeline

#### [NEW] Services & Controllers
- `CartService.php` & `CartController.php`:
  - Tambah produk / varian ke keranjang
  - Update kuantitas real-time via Alpine.js & AJAX
  - Hapus item keranjang
  - Checkbox pemilihan item untuk checkout
  - Hitung subtotal dinamis
- `WishlistController.php` & `wishlist/index.blade.php`:
  - Tambah/hapus produk favorit, tombol cepat pindah ke keranjang, empty state menarik
- `Customer/AddressController.php`:
  - Pengelolaan multi-alamat (tambah, edit, hapus, atur alamat utama)
- `CheckoutService.php` & `CheckoutController.php`:
  - Pilihan alamat pengiriman aktif / modal alamat baru
  - Pilihan opsi kurir pengiriman (Reguler, Hemat, Express) beserta ongkir
  - Input & validasi kode voucher via `VoucherService` (diskon persentase atau nominal tetap)
  - Pilihan metode pembayaran (Bank Transfer, Virtual Account, E-Wallet / QRIS, COD)
  - **Database Transaction + Pessimistic Row Locking (`lockForUpdate`)** untuk mengamankan stok produk/varian saat checkout dieksekusi

---

### FASE 4: Order Lifecycle, Payment, Review & Voucher System

#### [NEW] Order & Payment Workflow
- `OrderService.php` & `PaymentService.php`:
  - Generator kode pesanan unik format `ORD-YYYYMMDD-XXXXXX`
  - Transisi status pesanan: `pending` -> `paid` -> `processing` -> `shipped` -> `delivered` -> `completed` / `cancelled`
  - Dummy payment gateway handler (simulasi pembayaran sukses instan / transfer)
- `orders/index.blade.php` & `orders/show.blade.php`:
  - Riwayat pesanan dengan filter status
  - Halaman detail pesanan dengan timeline visual proses pengiriman
  - Tombol pembatalan pesanan jika status masih `pending`
  - Tombol bayar untuk pesanan pending
- `ReviewController.php`:
  - Form ulasan khusus untuk order yang berstatus `completed`
  - Validasi rating 1-5 bintang, komentar, dan upload foto
  - Proteksi: Penjual tidak dapat memberi review pada produknya sendiri

---

### FASE 5: Seller Dashboard & Store Operations

#### [NEW] Seller Management Module
- `layouts/seller.blade.php`: Tampilan sidebar & navbar dashboard seller profesional
- `Seller/DashboardController.php`: Metrik total penjualan, pesanan masuk, pendapatan, produk stok menipis
- `Seller/ShopController.php`: Edit profil toko, upload logo & banner toko
- `Seller/ProductController.php`:
  - CRUD produk lengkap dengan upload banyak gambar ke `storage/app/public/products`
  - Pengaturan variasi produk, harga, diskon, stok
- `Seller/OrderController.php`:
  - Daftar pesanan masuk untuk produk milik seller
  - Pemrosesan pesanan: Terima pesanan (`processing`), input nomor resi pengiriman (`shipped`)
- `Seller/ReportController.php`: Laporan ringkasan penjualan & pendapatan

---

### FASE 6: Admin Dashboard & Marketplace Moderation

#### [NEW] Admin Management Module
- `layouts/admin.blade.php`: Tampilan admin modern dengan collapsible sidebar & dark/light accent
- `Admin/DashboardController.php`:
  - Statistik platform (Total Users, Sellers, Products, Orders, Total GMV)
  - Chart ringkasan transaksi & pendaftaran pengguna
- `Admin/UserController.php`: Manajemen user & seller, blokir / aktifkan akun
- `Admin/CategoryController.php`: Manajemen hierarki kategori (parent/child, slug, ikon, upload gambar)
- `Admin/ProductController.php`: Moderasi produk sistem, hapus produk melanggar
- `Admin/OrderController.php`: Supervisi seluruh transaksi platform
- `Admin/MarketingController.php`: Manajemen banner promosi hero, voucher diskon, dan jadwal flash sale

---

### FASE 7: REST API Menggunakan Laravel Sanctum

#### [NEW] Sanctum API Endpoints & Resources
- `routes/api.php`
- `Api/AuthController.php`: Endpoint register, login, logout, get user profile
- `Api/ProductApiController.php`: List produk, filter, detail produk
- `Api/CategoryApiController.php`: List kategori
- `Api/CartApiController.php`: Manajemen keranjang belanja via API
- `Api/CheckoutApiController.php`: Checkout transaksi via API
- `Api/OrderApiController.php`: Riwayat pesanan & detail pesanan via API
- Format JSON respon seragam:
  - Sukses: `{"success": true, "message": "...", "data": {...}}`
  - Gagal: `{"success": false, "message": "...", "errors": {...}}`

---

### FASE 8: Testing, Security Hardening, Performance & SEO

#### [NEW] Testing & Optimization
- Unit & Feature Tests (`tests/Feature/AuthTest.php`, `CartTest.php`, `CheckoutTest.php`, `ProductTest.php`)
- Asset bundling via Vite (`npm run build`), konfigurasi Tailwind CSS dengan palet marketplace hijau
- Optimasi query (Eager loading `with()`, indeks database, pagination)
- SEO: Meta tag dinamis, OpenGraph, JSON-LD structured data schema `Product`
- Dokumentasi instalasi komprehensif pada `README.md` & `.env.example`

---

## Verification Plan

### Automated Tests
- Menjalankan migrasi bersih dan seeder:
  ```powershell
  php artisan migrate:fresh --seed
  ```
- Menjalankan automated test suite Laravel:
  ```powershell
  php artisan test
  ```

### Manual & Functional Verification
1. **Database & Seeder**: Memastikan semua tabel terbuat dengan relasi dan data awal (Admin, Seller, Customer, Kategori, Produk, Banner, Voucher).
2. **Katalog & Navigasi**: Buka homepage, uji slider banner, flash sale countdown, filter kategori & harga di `/products`, serta halaman detail produk.
3. **Transaksi Pembelian**: Login sebagai customer, tambah varian ke cart, masuk checkout, pilih alamat & kurir, apply voucher, lakukan order, dan verifikasi pengurangan stok yang atomik.
4. **Alur Seller**: Login sebagai seller, cek pesanan masuk, proses pesanan dan isi nomor resi.
5. **Alur Admin**: Login sebagai admin, cek dashboard statistik, tambah kategori baru, cek manajemen user dan banner.
6. **REST API**: Pengujian endpoint API auth, produk, dan cart menggunakan request JSON.
7. **Frontend Build**: Memastikan `npm run build` berhasil mengompilasi CSS dan JS tanpa error.
