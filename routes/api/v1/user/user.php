<?php

use App\Http\Controllers\Api\v1\User\Dish\DishController;
use App\Http\Controllers\Api\v1\User\User\UserCheckoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('checkout', [UserCheckoutController::class, 'checkout']);

        require_once __DIR__ . '/profile.php';
        require_once __DIR__ . '/menu.php';
        require_once __DIR__ . '/purchases.php';

        Route::prefix('dish')->group(function () {
            Route::prefix('time')->group(function () {
                Route::get('/', [DishController::class, 'time']);
                Route::get('/default', [DishController::class, 'timeDefaultSelect']);
            });
        });
    });

    require_once __DIR__ . '/telegram.php';
});
