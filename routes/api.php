<?php

use App\Http\Controllers\Api\ItemApiController;
use App\Http\Middleware\EnsureAdminKeyIsValid;
use Illuminate\Support\Facades\Route;

Route::get('/items', [ItemApiController::class, 'index']);

// Protected administration endpoints
Route::post('/items', [ItemApiController::class, 'store'])
    ->middleware(EnsureAdminKeyIsValid::class);