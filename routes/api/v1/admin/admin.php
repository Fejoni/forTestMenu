<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    require_once __DIR__ . '/dish.php';
    require_once __DIR__ . '/product.php';
});
