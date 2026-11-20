<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Toko Buah Pak Ridwan
|--------------------------------------------------------------------------
*/

// ==========================================================
// 1. PUBLIC & CUSTOMER SHOP ROUTES (Fruitables Theme)
// ==========================================================
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/shop', [ShopController::class, 'shop'])->name('shop');
Route::get('/shop/{id}', [ShopController::class, 'detail'])->name('shop.detail');
Route::get('/contact', [ShopController::class, 'contact'])->name('contact');

// Autentikasi Publik
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profil Pengguna (Bisa diakses user yang sedang login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// ==========================================================
// 2. CUSTOMER ONLY ROUTES (Keranjang, Checkout, Pesanan)
// ==========================================================
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/cart', [ShopController::class, 'cart'])->name('cart');
    Route::post('/cart/add', [ShopController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [ShopController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove/{id}', [ShopController::class, 'removeFromCart'])->name('cart.remove');

    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [ShopController::class, 'processCheckout'])->name('checkout.post');

    Route::get('/my-orders', [ShopController::class, 'myOrders'])->name('orders.my');
    Route::get('/orders/{id}', [ShopController::class, 'orderDetail'])->name('orders.detail');
    Route::post('/orders/{id}/cancel', [ShopController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/{id}/pay', [ShopController::class, 'uploadPaymentProof'])->name('orders.pay');
});

// ==========================================================
// 3. KASIR ROUTES
// ==========================================================
Route::prefix('kasir')->name('kasir.')->middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [KasirController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [KasirController::class, 'orderDetail'])->name('orders.detail');
    Route::post('/orders/{id}/status', [KasirController::class, 'updateOrderStatus'])->name('orders.status');

    // POS Toko (Transaksi langsung kasir offline)
    Route::get('/pos', [KasirController::class, 'pos'])->name('pos');
    Route::post('/pos', [KasirController::class, 'processPos'])->name('pos.post');

    // Validasi Pembayaran
    Route::get('/payments', [KasirController::class, 'payments'])->name('payments');
    Route::post('/payments/{id}/status', [KasirController::class, 'updatePaymentStatus'])->name('payments.status');

    // Pantau Stok & Promo
    Route::get('/stocks', [KasirController::class, 'stocks'])->name('stocks');

    // Rekap Penjualan Harian
    Route::get('/daily-report', [KasirController::class, 'dailyReport'])->name('daily-report');
});

// ==========================================================
// 4. ADMIN (OWNER / PAK RIDWAN) ROUTES
// ==========================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD Produk & Penyesuaian Stok
    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::post('/products/{id}/toggle', [AdminController::class, 'toggleProductStatus'])->name('products.toggle');
    Route::post('/products/stock-adjust', [AdminController::class, 'stockAdjustment'])->name('products.stock-adjust');
    Route::get('/products/stock-history', [AdminController::class, 'stockHistory'])->name('products.stock-history');

    // CRUD Kategori
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

    // CRUD Promo Diskon
    Route::get('/promos', [AdminController::class, 'promos'])->name('promos.index');
    Route::post('/promos', [AdminController::class, 'storePromo'])->name('promos.store');
    Route::put('/promos/{id}', [AdminController::class, 'updatePromo'])->name('promos.update');
    Route::delete('/promos/{id}', [AdminController::class, 'destroyPromo'])->name('promos.destroy');

    // CRUD Area Antar
    Route::get('/delivery-areas', [AdminController::class, 'deliveryAreas'])->name('areas.index');
    Route::post('/delivery-areas', [AdminController::class, 'storeDeliveryArea'])->name('areas.store');
    Route::put('/delivery-areas/{id}', [AdminController::class, 'updateDeliveryArea'])->name('areas.update');
    Route::delete('/delivery-areas/{id}', [AdminController::class, 'destroyDeliveryArea'])->name('areas.destroy');

    // Manajemen Akun (Kasir & Pelanggan)
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::post('/users/kasir', [AdminController::class, 'storeKasir'])->name('users.kasir.store');
    Route::put('/users/kasir/{id}', [AdminController::class, 'updateKasir'])->name('users.kasir.update');
    Route::post('/users/{id}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');

    // Semua Pesanan Transaksi
    Route::get('/orders', [AdminController::class, 'allOrders'])->name('orders.index');

    // Laporan Penjualan Lengkap & Buah Terlaris
    Route::get('/reports/sales', [AdminController::class, 'salesReport'])->name('reports.sales');
});
