<?php

use App\Http\Controllers\Api\v1\User\UserCheckoutController;
use App\Http\Controllers\Api\v1\User\UserLoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::post('login', [UserLoginController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('checkout', [UserCheckoutController::class, 'checkout']);

        require_once __DIR__ . '/profile.php';
        require_once __DIR__ . '/menu.php';
    });

    require_once __DIR__ . '/telegram.php';
});
