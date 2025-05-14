<?php

use App\Http\Controllers\Api\v1\Admin\AdminLoginController;
use App\Http\Controllers\Api\v1\Admin\Image\ImageUploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminLoginController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        require_once __DIR__ . '/dish.php';
        require_once __DIR__ . '/product.php';

        Route::prefix('file')->group(function () {
            Route::post('upload', [ImageUploadController::class, 'upload']);
        });
    });
});
