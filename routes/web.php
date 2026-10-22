<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiteController;
use App\Http\Middleware\DemoAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/login', [SiteController::class, 'loginForm'])->name('login');
Route::post('/login', [SiteController::class, 'login'])->name('login.post');
Route::post('/logout', [SiteController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware(DemoAdmin::class)->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/produk', [AdminController::class, 'products'])->name('products');
    Route::get('/produk/tambah', [AdminController::class, 'productCreate'])->name('products.create');
    Route::get('/pesanan', [AdminController::class, 'orders'])->name('orders');
    Route::get('/pesanan/{id}', [AdminController::class, 'orderShow'])->name('orders.show');
    Route::get('/klien', [AdminController::class, 'clients'])->name('clients');
});
