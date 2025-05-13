<?php

use App\Http\Controllers\Api\v1\Telegram\User\UserController;
use App\Http\Controllers\Api\v1\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('profile')->group(function () {
    Route::prefix('update')->group(function () {
        Route::post('/', [UserProfileController::class, 'update']);
        Route::post('first-view', [UserController::class, 'update']);
    });
});
