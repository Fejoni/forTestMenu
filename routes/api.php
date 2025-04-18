<?php

use App\Http\Controllers\Api\v1\User\UserCheckoutController;
use App\Http\Controllers\Api\v1\User\UserLoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('login', [UserLoginController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('checkout', [UserCheckoutController::class, 'checkout']);
        });
    });

    Route::prefix('telegram')->group(function () {

    });
});
