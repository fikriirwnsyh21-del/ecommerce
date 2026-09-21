# PasarKeren - Modern Multi-Vendor E-Commerce Marketplace

**PasarKeren** adalah platform marketplace e-commerce multi-vendor modern yang dibangun menggunakan **Laravel 13**, **PHP 8.3+**, **MySQL 8+**, **Blade**, **Tailwind CSS v4**, **Alpine.js**, dan **Laravel Sanctum**. Konsep dan pengalaman pengguna (UX) terinspirasi dari marketplace terkemuka seperti Tokopedia, dengan palet warna hijau segar (*emerald* `#00AA5B` / `emerald-600`), navigasi responsif, keranjang belanja multi-toko, mitigasi race condition stok melalui *pessimistic row locking*, manajemen toko seller mandiri, moderasi admin terpusat, serta REST API v1 siap integrasi mobile apps.

---

## 🌟 Fitur Utama Berdasarkan Peran

### 1. Pelanggan (Customer)
* **Katalog & Navigasi**:
  * Hero banner slider promosi interaktif.
  * Navigasi kategori belanja dengan icon visual.
  * Flash Sale dengan hitung mundur waktu (*countdown timer*) berbasis Alpine.js.
  * Filter multi-kriteria: kategori, rentang harga, rating bintang, urutan (termurah, termahal, terlaris, rating tertinggi).
  * Halaman detail produk komprehensif: galeri multi-foto, varian dinamis (warna/ukuran/spesifikasi) dengan penyesuaian harga real-time, badge garansi & estimasi pengiriman.
* **Profil & Showcase Toko**:
  * Informasi toko, badge reputasi, lokasi kota, statistik rating toko, dan katalog produk khusus toko tersebut.
* **Keranjang Belanja Multi-Toko**:
  * Pengelompokan produk berdasarkan toko penjual.
  * Fitur centang pilihan item (*checkbox item & pilih semua*) untuk fleksibilitas checkout sebagian.
  * Beli Langsung (*Buy Now*) langsung mengarah ke proses pesanan.
* **Wishlist**:
  * Simpan produk favorit & pindahkan langsung ke keranjang dengan 1 klik.
* **Buku Alamat Pengiriman**:
  * Pengelolaan multi-alamat penerima lengkap dengan penanda alamat utama (*primary*).
* **Checkout Aman & Kupon Promo**:
  * Validasi voucher promo platform (*nominal tetap / persentase diskon*) dengan syarat belanja minimum.
  * Pilihan kurir pengiriman (JNE, SiCepat, J&T, GoSend, dsb.).
  * Pilihan metode pembayaran (Transfer Bank Virtual Account, E-Wallet QRIS, COD).
  * **Pessimistic Row Locking (`lockForUpdate`)**: Menjamin stok produk tidak akan pernah bernilai negatif meskipun terjadi lonjakan transaksi checkout secara bersamaan (*race condition prevention*).
* **Riwayat Pesanan & Ulasan**:
  * Pelacakan status pesanan: `pending` → `paid` → `processing` → `shipped` → `delivered` → `completed` / `cancelled`.
  * Tombol simulasi bayar instan untuk pengujian checkout.
  * Pembatalan pesanan berstatus *pending* dengan otomatisasi pengembalian stok (*inventory rollback*).
  * Konfirmasi penerimaan barang & sistem ulasan produk (*rating bintang 1-5, foto, dan ulasan teks*) yang secara otomatis memperbarui agregat rating toko dan produk.

---

### 2. Mitra Penjual (Seller Center)
* **Dashboard Penjual**:
  * Ringkasan pendapatan bersih (*revenue*), pesanan baru yang harus diproses, total katalog aktif, dan peringatan stok menipis (≤5 unit).
* **Manajemen Produk**:
  * Daftar produk toko dengan fitur pencarian, filter status (Aktif, Nonaktif, Stok Menipis), dan tombol cepat aktifkan/nonaktifkan.
  * Form Tambah & Edit Produk: Multi-upload foto, kategori, berat, kondisi barang (Baru/Bekas), harga coret/diskon, dan repeater dinamis varian produk berbasis Alpine.js.
* **Kelola Pesanan Masuk**:
  * Filter status pesanan yang memiliki item dari toko penjual.
  * Fitur terima dan proses pesanan (`paid` → `processing`).
  * Fitur pengiriman dengan input nomor resi pelacakan kurir (`processing` → `shipped` + `tracking_number`).
* **Pengaturan Toko**:
  * Ubah nama toko, deskripsi/slogan, alamat gudang asal pengiriman, serta upload logo dan banner toko.

---

### 3. Super Admin (Platform Control)
* **Dashboard Platform**:
  * Metrik GMV (*Gross Merchandise Value*), total transaksi platform, total pengguna terdaftar, jumlah toko aktif, dan total katalog SKU.
  * Distribusi pesanan dan kategori terpopuler.
* **Manajemen Pengguna**:
  * Daftar seluruh akun (Admin, Seller, Customer).
  * Filter role dan status akun.
  * Fitur moderasi: tombol suspend / aktifkan kembali akun pengguna.
* **Manajemen Kategori**:
  * Tambah, edit, dan hapus kategori belanja dengan dukungan hierarki *parent-child* serta ikon/gambar.
* **Moderasi Produk Global**:
  * Pengawasan seluruh katalog produk lintas toko.
  * Tindakan take down (nonaktifkan paksa) atau penghapusan produk yang melanggar aturan.
* **Inspeksi Seluruh Transaksi**:
  * Monitoring menyeluruh terhadap seluruh order, rincian biaya, kurir, dan pembayaran.
* **Marketing & Promosi Platform**:
  * Pengelolaan banner slider beranda (*hero slider, promo banner, sort order*).
  * Pengelolaan voucher diskon platform (kode kupon unik, kuota pemakaian, masa berlaku, potongan harga).

---

### 4. REST API v1 (Laravel Sanctum)
REST API versi 1 siap digunakan untuk integrasi Mobile Apps (Android/iOS) atau Frontend SPA terpisah:
* `POST /api/v1/auth/login`: Otentikasi dan penerbitan personal access token Sanctum.
* `POST /api/v1/auth/register`: Pendaftaran customer atau pendaftaran toko mitra penjual.
* `GET  /api/v1/auth/me`: Informasi profil pengguna yang sedang login (`Bearer Token`).
* `POST /api/v1/auth/logout`: Revoke/hapus token otentikasi.
* `GET  /api/v1/products`: Katalog produk publik dengan pencarian, kategori, pengurutan harga/terlaris, dan paginasi.
* `GET  /api/v1/products/{slugOrId}`: Detail produk lengkap beserta varian, foto, dan toko.
* `GET  /api/v1/categories`: Daftar seluruh kategori aktif beserta sub-kategorinya.
* `GET  /api/v1/cart`: Daftar item dalam keranjang belanja pengguna.
* `POST /api/v1/cart/add`: Menambahkan item dan varian ke keranjang belanja.
* `PUT  /api/v1/cart/{id}`: Mengubah kuantitas item keranjang.
* `DELETE /api/v1/cart/{id}`: Menghapus item dari keranjang.
* `POST /api/v1/checkout`: Eksekusi checkout transaksi dengan locking stok atomik.
* `GET  /api/v1/orders`: Daftar transaksi pesanan pengguna.
* `GET  /api/v1/orders/{id}`: Detail pesanan.
* `POST /api/v1/orders/{id}/pay`: Simulasi konfirmasi pembayaran instan.
* `POST /api/v1/orders/{id}/cancel`: Pembatalan pesanan dan pengembalian stok.

---

## 🔐 Akun Uji Coba Default (Demo Credentials)

Semua akun menggunakan kata sandi yang sama: `password`

| Peran (Role) | Email | Kata Sandi | Akses Halaman |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@example.com` | `password` | `/admin/dashboard` |
| **Penjual (Seller)** | `seller@example.com` | `password` | `/seller/dashboard` |
| **Pelanggan (Customer)**| `customer@example.com` | `password` | `/` (Marketplace) |

*(Mitra penjual lainnya yang tersedia dari seeder: `seller2@example.com`, `seller3@example.com`, `seller4@example.com`, `seller5@example.com`)*

---

## 🛠️ Persyaratan Sistem & Instalasi

### Persyaratan
- PHP >= 8.3 (dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`/`imagick`, `curl`)
- MySQL >= 8.0
- Composer >= 2.x
- Node.js >= 18.x & npm

### Langkah Instalasi
1. **Clone Repository / Navigasi ke direktori project**:
   ```bash
   cd d:/ecommerce
   ```

2. **Salin environment file & konfigurasi database**:
   ```bash
   cp .env.example .env
   # Pastikan DB_DATABASE=ecommerce, DB_USERNAME=root, DB_PASSWORD= sesuai environment Anda
   ```

3. **Install dependensi PHP**:
   ```bash
   composer install
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi & Database Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Buat Symlink Storage untuk Berkas Gambar**:
   ```bash
   php artisan storage:link
   ```

7. **Install dependensi Frontend & Build Assets**:
   ```bash
   npm install
   npm run build
   ```

8. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui peramban di: `http://localhost:8000`

---

## 🧪 Pengujian Otomatis (Automated Tests)

PasarKeren dilengkapi dengan cakupan pengujian komprehensif menggunakan PHPUnit dan database in-memory:

```bash
php artisan test
```

### Hasil Ringkasan Pengujian:
- **Total Pengujian**: 60 Tests
- **Total Assertions**: 177 Assertions
- **Tingkat Kelulusan**: **100% Passed (0 Failed, 0 Skipped)**
- **Feature Suites**:
  1. `AuthTest`: Registrasi customer & seller, login, logout, proteksi hak akses.
  2. `ProductCatalogTest`: Beranda, slider banner, filter katalog multi-parameter, detail produk, showcase toko.
  3. `CartServiceTest` & `CartTest`: Operasi keranjang belanja, manipulasi kuantitas, pencegahan stok berlebih.
  4. `VoucherServiceTest`: Validasi diskon nominal, diskon persentase, batas kuota dan tanggal kadaluarsa.
  5. `CheckoutServiceTest` & `CheckoutTest`: Transaksi checkout atomik, proteksi race condition (*pessimistic row locking*).
  6. `OrderReviewTest`: Siklus status pesanan, simulasi bayar, pembatalan dan pemulihan stok, ulasan & rating bintang.
  7. `SellerTest`: Dashboard seller, CRUD produk & varian, update profil toko, pemrosesan order dan input resi kirim.
  8. `AdminTest`: Dashboard platform GMV, moderasi pengguna & toko, CRUD kategori, take down produk, voucher platform.
  9. `ApiTest`: Endpoint REST API v1 Sanctum (Auth, Catalog, Cart, Checkout, Orders).

---

## 📐 Arsitektur & Standar Koding
- **Separation of Concerns**: Menggunakan *Dedicated Service Classes* (`CartService`, `CheckoutService`, `VoucherService`) untuk memisahkan domain business logic dari Controller.
- **Data Integrity**: Memanfaatkan database transactions (`DB::transaction`) dan row-level locking (`lockForUpdate`) untuk memastikan integritas data keuangan dan inventori.
- **Security & Authorization**: Penggunaan Laravel Policies (`OrderPolicy`, `ProductPolicy`, `ShopPolicy`) serta middleware (`role:admin`, `role:seller`, `auth:sanctum`).
- **Clean Code Style**: Diformat mengikuti standar PSR-12 dan Laravel Pint (`php vendor/bin/pint --format agent`).
