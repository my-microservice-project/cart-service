<?php

use App\Http\Controllers\V1\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('cart')->group(function () {
        Route::put('{productId}/{quantity}', [CartController::class, 'update']);
        Route::delete('flush', [CartController::class, 'clear']);
    });

    Route::resource('cart', CartController::class)->except('update');

});

