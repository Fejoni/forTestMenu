<?php

use App\Http\Controllers\Api\v1\Admin\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::post('login', [AdminLoginController::class, 'login']);

    require_once __DIR__ . '/dish.php';
    require_once __DIR__ . '/product.php';
});
