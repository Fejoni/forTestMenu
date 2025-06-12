<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require_once __DIR__ . '/api/v1/admin/admin.php';
    require_once __DIR__ . '/api/v1/user/user.php';
});
