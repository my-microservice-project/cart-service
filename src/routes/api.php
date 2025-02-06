<?php

use App\Http\Controllers\V1\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'get']);
        Route::post('/add', [CartController::class, 'add']);
        Route::delete('/remove/{productId}', [CartController::class, 'remove']);
        Route::put('/update/{productId}/{quantity}', [CartController::class, 'update']);
        Route::delete('/flush', [CartController::class, 'clear']);
    });
});

