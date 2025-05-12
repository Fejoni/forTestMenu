<?php

use App\Http\Controllers\Api\v1\Image\ImageUploadController;
use App\Http\Controllers\Api\v1\User\UserCheckoutController;
use App\Http\Controllers\Api\v1\User\UserLoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('login', [UserLoginController::class, 'login']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('file')->group(function () {
            Route::post('upload', [ImageUploadController::class, 'upload']);
        });

        require_once __DIR__ . '/api/user.php';
        require_once __DIR__ . '/api/product.php';
        require_once __DIR__ . '/api/dish.php';
    });
});

require_once __DIR__ . '/telegram.php';
