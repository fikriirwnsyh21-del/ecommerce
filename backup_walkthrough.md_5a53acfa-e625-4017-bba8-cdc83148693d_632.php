# Walkthrough: PasarKeren E-Commerce Marketplace (Laravel 13)

Platform marketplace multi-vendor **PasarKeren** telah selesai dibangun secara menyeluruh mulai dari arsitektur database, antarmuka marketplace pelanggan, modul mitra penjual (*Seller Center*), modul kontrol admin (*Platform Backoffice*), hingga REST API v1 berbasis Laravel Sanctum. Seluruh 60 unit/feature test telah berhasil dijalankan dengan kelulusan 100%.

---

## 🚀 Ringkasan Capaian & Eksekusi

### 1. Fondasi & Arsitektur (Phase 1)
- **Framework & Runtime**: Laravel 13 running on PHP 8.3.33 (CLI) & MySQL 8.0.30 daemon.
- **Frontend Engine**: Vite + Tailwind CSS v4 + Alpine.js.
- **18 Migrasi Tabel**: `roles`, `users`, `categories`, `shops`, `products`, `product_images`, `product_variants`, `addresses`, `carts`, `cart_items`, `orders`, `order_items`, `payments`, `reviews`, `vouchers`, `voucher_usages`, `flash_sales`, `banners`, `wishlists`, `personal_access_tokens`.
- **Database Seeder Realistis**: Akun Admin, 5 Toko Mitra Penjual, Akun Pembeli, 10 Kategori, 20 Produk bervarian dan bergambar, Flash Sale aktif, Banners hero slider, Vouchers diskon, serta transaksi riwayat pesanan.

### 2. Marketplace & Katalog Pelanggan (Phase 2)
- **Beranda (Tokopedia Style)**:
  - Hero banner slider dengan auto-slide & navigasi indikator.
  - Grid kategori interaktif dengan ikon visual.
  - Flash sale dengan hitung mundur waktu (*countdown timer*) berbasis Alpine.js.
  - Bagian produk terlaris & rekomendasi pilihan.
- **Katalog & Filter**:
  - Filter multi-kriteria: Kategori, rentang harga minimal/maksimal, rating bintang, dan sorting (harga terendah/tertinggi, terlaris, rating).
  - Search bar global dengan respons cepat.
- **Detail Produk**:
  - Galeri multi-foto dengan zoom dan switch foto utama.
  - Switcher varian (warna/ukuran) dinamis yang otomatis menyesuaikan harga.
  - Tab deskripsi lengkap, spesifikasi, review pembeli, serta estimasi ongkos kirim.
- **Profil Toko**:
  - Showcase toko dengan reputasi bintang, lokasi kota, deskripsi, dan katalog produk khusus toko tersebut.

### 3. Keranjang, Alamat & Checkout Aman (Phase 3)
- **Keranjang Multi-Toko**:
  - Pengelompokan barang berdasarkan toko penjual.
  - Checkbox pemilihan item & tombol pilih semua.
  - Update kuantitas real-time.
- **Buku Alamat Pengiriman**:
  - CRUD alamat lengkap penerima dengan switch alamat utama.
- **Pessimistic Row Locking (`lockForUpdate`)**:
  - Atomic stock reservation saat checkout di dalam `DB::transaction()`.
  - Mencegah *race condition* dan menjamin stok tidak pernah menjadi minus.
  - Dukungan voucher kupon platform (nominal tetap atau persentase).

### 4. Sistem Pesanan, Pembayaran & Ulasan (Phase 4)
- **Alur Status Pesanan**: `pending` → `paid` → `processing` → `shipped` → `delivered` → `completed` / `cancelled`.
- **Simulasi Bayar Instan**: Tombol bayar untuk simulasi testing seketika.
- **Inventory Rollback**: Pembatalan pesanan berstatus *pending* otomatis mengembalikan kuantitas stok produk dan varian.
- **Sistem Ulasan Produk**: Pembeli pada pesanan berstatus *completed* dapat memberikan rating bintang (1-5), komentar, dan foto ulasan yang langsung memperbarui agregat rating produk.

### 5. Mitra Penjual / Seller Center (Phase 5)
- **Layout Mandiri**: Sidebar navigasi khas Tokopedia Seller Center.
- **Dashboard Penjual**: Metrik pendapatan toko (*revenue*), pesanan masuk, jumlah produk, dan peringatan stok menipis (≤5 unit).
- **Katalog Produk Seller**: Tambah produk baru dengan upload multi-foto, kategori, berat, kondisi barang, dan varian repeater berbasis Alpine.js. Fitur edit, hapus, dan quick toggle status aktif/nonaktif.
- **Kelola Pesanan Masuk**: Filter status pesanan, proses pesanan (`paid` → `processing`), dan pengiriman barang dengan memasukkan nomor resi ekspedisi (`processing` → `shipped` + `tracking_number`).
- **Profil Toko**: Pengaturan nama toko, slogan, alamat asal pengiriman, serta upload logo dan banner toko.

### 6. Platform Control / Super Admin (Phase 6)
- **Backoffice Admin**: Dashboard metrik GMV platform, volume transaksi, jumlah pengguna, toko, dan SKU katalog.
- **Manajemen Pengguna**: Monitoring akun pengguna lintas role (Admin, Seller, Customer) dengan fungsi suspensi / re-aktivasi akun.
- **Manajemen Kategori**: CRUD kategori produk dengan hierarki induk (*parent-child*), ikon, dan pengurutan (*sort order*).
- **Moderasi Produk Global**: Pengawasan katalog produk seluruh seller dengan fitur take down (nonaktifkan paksa) atau hapus permanen jika terbukti melanggar aturan.
- **Inspeksi Transaksi**: Akses inspeksi terhadap detail seluruh pesanan, alamat tujuan, rincian biaya, dan kurir.
- **Promosi Platform**: Pengelolaan banner slider beranda dan pembuatan voucher diskon platform.

### 7. REST API v1 Laravel Sanctum (Phase 7)
- **Token Otentikasi**: Endpoint `login`, `register`, `me`, dan `logout` dengan Sanctum personal access tokens.
- **Public Endpoints**: `GET /api/v1/products`, `GET /api/v1/products/{id}`, `GET /api/v1/categories`.
- **Customer Endpoints**: `GET/POST/PUT/DELETE /api/v1/cart`, `POST /api/v1/checkout`, `GET /api/v1/orders`, `POST /api/v1/orders/{id}/pay`, `POST /api/v1/orders/{id}/cancel`.
- **Konsistensi Respons**: Menggunakan Eloquent API Resources dengan struktur standar `{ success, message, data, meta }`.

---

## 🧪 Hasil Verifikasi & Pengujian Otomatis

Seluruh pengujian otomatis dijalankan menggunakan PHPUnit:

```bash
php artisan test --compact
```

```json
{"tool":"phpunit","result":"passed","tests":60,"passed":60,"assertions":177,"duration_ms":2771}
```

### Breakdown Suite:
1. `AuthTest`: 6 passed
2. `ProductCatalogTest`: 6 passed
3. `CartServiceTest` & `CartTest`: 5 passed
4. `VoucherServiceTest`: 3 passed
5. `CheckoutServiceTest` & `CheckoutTest`: 4 passed
6. `OrderReviewTest`: 8 passed
7. `SellerTest`: 10 passed
8. `AdminTest`: 8 passed
9. `ApiTest`: 8 passed
10. `ExampleTest`: 2 passed

**Total: 60 Tests, 177 Assertions, 0 Failure.**

---

## 🔑 Akun Demo Siap Pakai

Semua akun menggunakan password: `password`

1. **Super Admin**: `admin@example.com` (Akses: `/admin/dashboard`)
2. **Mitra Penjual**: `seller@example.com` (Akses: `/seller/dashboard`)
3. **Pelanggan**: `customer@example.com` (Akses: Marketplace `/`)

---

## 📦 File Kunci yang Dibangun

- **Layouts**:
  - `resources/views/layouts/app.blade.php` (Marketplace Pelanggan)
  - `resources/views/layouts/seller.blade.php` (Seller Center)
  - `resources/views/layouts/admin.blade.php` (Admin Platform Control)
- **Services**:
  - `app/Services/CartService.php`
  - `app/Services/VoucherService.php`
  - `app/Services/CheckoutService.php`
- **Policies**:
  - `app/Policies/OrderPolicy.php`
  - `app/Policies/ProductPolicy.php`
  - `app/Policies/ShopPolicy.php`
- **REST API Resources & Controllers**:
  - `app/Http/Resources/*` (User, Product, Category, CartItem, Order)
  - `app/Http/Controllers/Api/V1/*`
- **Dokumentasi Lengkap**:
  - `d:/ecommerce/README.md`
