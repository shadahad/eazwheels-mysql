<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StorefrontController;
use App\Http\Middleware\EnsureAdminKeyIsValid;
use Illuminate\Support\Facades\Route;

// Module B8: Public Storefront
Route::get('/', [StorefrontController::class, 'index'])->name('storefront.home');
Route::post('/enquire', [StorefrontController::class, 'enquire'])->name('storefront.enquire');

// Module B1: Admin Interface (Key protected)
Route::prefix('admin')->middleware(EnsureAdminKeyIsValid::class)->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/items', [AdminController::class, 'store'])->name('admin.items.store');
    Route::post('/items/{id}/stock', [AdminController::class, 'updateStock'])->name('admin.items.stock');
});