<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

// Module B8: Public Storefront
Route::get('/', [StorefrontController::class, 'index'])->name('storefront.home');
Route::post('/enquire', [StorefrontController::class, 'enquire'])->name('storefront.enquire');

// Admin Auth Routes (Public)
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

// Module B1: Admin Interface (Session Protected)
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/items', [AdminController::class, 'store'])->name('admin.items.store');
    Route::put('/items/{id}', [AdminController::class, 'update'])->name('admin.items.update');
    Route::post('/items/{id}/stock', [AdminController::class, 'updateStock'])->name('admin.items.stock');
});