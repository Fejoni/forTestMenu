<?php

use App\Http\Controllers\Api\v1\Admin\AdminAuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::post('login', [AdminAuthenticationController::class, 'login']);

    require_once __DIR__ . '/dish.php';
    require_once __DIR__ . '/product.php';
});
