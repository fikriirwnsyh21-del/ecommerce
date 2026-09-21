<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PasarKeren E-Commerce Marketplace
|--------------------------------------------------------------------------
*/

// 1. Homepage & Public Catalog
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shops.show');

// 2. Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// 3. Authenticated Customer Shopping & Account Routes
Route::middleware('auth')->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buy-now');
    Route::put('/cart/item/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/item/{id}/toggle', [CartController::class, 'toggle'])->name('cart.toggle');
    Route::post('/cart/toggle-all', [CartController::class, 'toggleAll'])->name('cart.toggle-all');
    Route::delete('/cart/item/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/{product}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Address Routes
    Route::get('/account/addresses', [AddressController::class, 'index'])->name('customer.addresses.index');
    Route::post('/account/addresses', [AddressController::class, 'store'])->name('customer.addresses.store');
    Route::put('/account/addresses/{address}', [AddressController::class, 'update'])->name('customer.addresses.update');
    Route::post('/account/addresses/{address}/primary', [AddressController::class, 'setPrimary'])->name('customer.addresses.primary');
    Route::delete('/account/addresses/{address}', [AddressController::class, 'destroy'])->name('customer.addresses.destroy');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/voucher', [CheckoutController::class, 'checkVoucher'])->name('checkout.voucher');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Account Profile
    Route::get('/account/profile', function () {
        return redirect()->route('customer.addresses.index');
    })->name('customer.profile');

    // Phase 4: Order System & Review Routes
    Route::get('/account/orders', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/payment-status', [OrderController::class, 'paymentStatus'])->name('orders.payment-status');
    Route::post('/orders/{id}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/confirm-delivered', [OrderController::class, 'confirmDelivered'])->name('orders.confirm-delivered');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Phase 6: Admin Platform Control Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Categories Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Products Moderation
    Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::post('/products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::delete('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

    // Orders Oversight
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');

    // Marketing (Banners & Vouchers)
    Route::get('/marketing/banners', [MarketingController::class, 'banners'])->name('marketing.banners');
    Route::post('/marketing/banners', [MarketingController::class, 'storeBanner'])->name('marketing.banners.store');
    Route::post('/marketing/banners/{banner}/toggle', [MarketingController::class, 'toggleBanner'])->name('marketing.banners.toggle');
    Route::delete('/marketing/banners/{banner}', [MarketingController::class, 'destroyBanner'])->name('marketing.banners.destroy');

    Route::get('/marketing/vouchers', [MarketingController::class, 'vouchers'])->name('marketing.vouchers');
    Route::post('/marketing/vouchers', [MarketingController::class, 'storeVoucher'])->name('marketing.vouchers.store');
    Route::post('/marketing/vouchers/{voucher}/toggle', [MarketingController::class, 'toggleVoucher'])->name('marketing.vouchers.toggle');
    Route::delete('/marketing/vouchers/{voucher}', [MarketingController::class, 'destroyVoucher'])->name('marketing.vouchers.destroy');
});

// Phase 5: Seller Center Routes
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::get('/products', [App\Http\Controllers\Seller\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [App\Http\Controllers\Seller\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Seller\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [App\Http\Controllers\Seller\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\Seller\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\Seller\ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle-active', [App\Http\Controllers\Seller\ProductController::class, 'toggleActive'])->name('products.toggle-active');

    // Orders Management
    Route::get('/orders', [App\Http\Controllers\Seller\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\Seller\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/process', [App\Http\Controllers\Seller\OrderController::class, 'process'])->name('orders.process');
    Route::post('/orders/{id}/ship', [App\Http\Controllers\Seller\OrderController::class, 'ship'])->name('orders.ship');

    // Shop Settings
    Route::get('/shop', [App\Http\Controllers\Seller\ShopController::class, 'edit'])->name('shop.edit');
    Route::put('/shop', [App\Http\Controllers\Seller\ShopController::class, 'update'])->name('shop.update');
});
