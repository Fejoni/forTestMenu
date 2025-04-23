<?php

use App\Http\Controllers\Api\v1\Telegram\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('telegram')->group(function () {
        Route::prefix('user')->group(function () {
            Route::post('/checkout', [UserController::class, 'checkout']);

            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/', [UserController::class, 'index']);
            });
        });
    });
});
