<?php

use App\Http\Controllers\Api\v1\User\UserCheckoutController;
use App\Http\Controllers\Api\v1\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::post('checkout', [UserCheckoutController::class, 'checkout']);

    Route::prefix('profile')->group(function () {
        Route::post('update', [UserProfileController::class, 'update']);
    });

    require_once __DIR__ . '/menu.php';
});
