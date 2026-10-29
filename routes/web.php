<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [SiteController::class, 'loginForm'])->name('login');
    Route::post('/login', [SiteController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
});

Route::post('/logout', [SiteController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/produk', [ProductController::class, 'index'])->name('products');
    Route::get('/produk/tambah', [ProductController::class, 'create'])->name('products.create');
    Route::post('/produk', [ProductController::class, 'store'])->name('products.store');
    Route::get('/produk/{product}', [ProductController::class, 'show'])->whereNumber('product')->name('products.show');
    Route::get('/produk/{product}/ubah', [ProductController::class, 'edit'])->whereNumber('product')->name('products.edit');
    Route::put('/produk/{product}', [ProductController::class, 'update'])->whereNumber('product')->name('products.update');
    Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->whereNumber('product')->name('products.destroy');

    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->whereNumber('order')->name('orders.show');
    Route::patch('/pesanan/{order}/status', [OrderController::class, 'updateStatus'])->whereNumber('order')->name('orders.status');

    Route::get('/klien', [AdminController::class, 'clients'])->name('clients');
    Route::get('/klien/{user}', [AdminController::class, 'clientShow'])->whereNumber('user')->name('clients.show');

    Route::get('/pengaturan', [SettingController::class, 'edit'])->name('settings');
    Route::put('/pengaturan', [SettingController::class, 'update'])->name('settings.update');
});
