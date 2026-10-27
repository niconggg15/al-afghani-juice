<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Cashier\OrderController as CashierOrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes - Al Afghani Juice
|--------------------------------------------------------------------------
*/

// Redirect Halaman Utama ke Menu Customer
Route::get('/', function () {
    return redirect()->route('customer.menu');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Customer Routes - Menu
Route::prefix('menu')->name('customer.')->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('menu');
    Route::get('/{product}', [MenuController::class, 'show'])->name('product.show');
});

// Customer Routes - Keranjang (Cart)
Route::prefix('cart')->name('customer.cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{productId?}', [CartController::class, 'add'])->name('add');
    Route::match(['put', 'patch'], '/{productId}', [CartController::class, 'update'])->name('update');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::delete('/{productId}', [CartController::class, 'remove'])->name('remove');
});

// Customer Routes - Checkout
Route::prefix('checkout')->name('customer.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('order.success');
});

// Cashier Routes - Kelola Pesanan, Uang Masukan Cash, & QRIS
Route::prefix('cashier')->name('cashier.')->middleware('auth')->group(function () {
    Route::get('/orders/history', [CashierOrderController::class, 'history'])
        ->middleware('role:cashier,admin,analyst')
        ->name('orders.history');

    Route::middleware('role:cashier,admin')->group(function () {
        Route::get('/', [CashierDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [CashierOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [CashierOrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [CashierOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/confirm-payment', [CashierOrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
        Route::post('/orders/{order}/confirm-cash', [CashierOrderController::class, 'confirmCashPayment'])->name('orders.confirm-cash');
        Route::post('/orders/{order}/auto-qris', [CashierOrderController::class, 'autoConfirmQris'])->name('orders.auto-qris');
    });
});

// Admin Routes - Kelola Kategori, Produk, & Stok Jus
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('products/create', [ProductController::class, 'create'])
        ->middleware('role:admin')
        ->name('products.create');

    Route::get('/', function () {
        return view('admin.dashboard');
    })->middleware('role:admin,analyst,cashier')->name('dashboard');

    Route::middleware('role:admin,analyst')->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    });
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['index', 'show']);
        Route::resource('categories', CategoryController::class)->except(['index', 'show']);
        Route::resource('products', ProductController::class)->except(['index', 'show', 'create']);
        Route::put('products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
    });
});