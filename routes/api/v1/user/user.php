<?php

use App\Http\Controllers\Api\v1\Menu\MenuController;
use App\Http\Controllers\Api\v1\Menu\MenuFoodController;
use App\Http\Controllers\Api\v1\Telegram\User\UserController;
use App\Http\Controllers\Api\v1\User\UserCheckoutController;
use App\Http\Controllers\Api\v1\User\UserLoginController;
use App\Http\Controllers\Api\v1\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::post('login', [UserLoginController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('checkout', [UserCheckoutController::class, 'checkout']);

        Route::prefix('profile')->group(function () {
            Route::prefix('update')->group(function () {
                Route::post('/', [UserProfileController::class, 'update']);
                Route::post('first-view', [UserController::class, 'update']);
            });
        });

        Route::prefix('menu')->group(function () {
            Route::post('/', [MenuController::class, 'index']);
            Route::post('/generate', [MenuController::class, 'generate']);
            Route::post('/repeat/generate', [MenuController::class, 'repeatGenerate']);

            Route::prefix('food')->group(function () {
                Route::delete('/delete', [MenuFoodController::class, 'delete']);
                Route::post('/repeat', [MenuFoodController::class, 'repeat']);
            });
        });
    });

    Route::prefix('telegram')->group(function () {
        Route::post('/checkout', [UserController::class, 'checkout']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [UserController::class, 'index']);
        });
    });
});
