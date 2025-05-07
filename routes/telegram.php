<?php

use App\Http\Controllers\Api\v1\Telegram\FamilyController;
use App\Http\Controllers\Api\v1\Telegram\MenuController;
use App\Http\Controllers\Api\v1\Telegram\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('telegram')->group(function () {
        Route::prefix('user')->group(function () {
            Route::post('/checkout', [UserController::class, 'checkout']);

            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/', [UserController::class, 'index']);
                Route::post('/update', [UserController::class, 'update']);

                Route::prefix('family')->group(function () {
                    Route::post('add', [FamilyController::class, 'addFamily']);
                    Route::post('status', [FamilyController::class, 'status']);
                });

                Route::prefix('menu')->group(function () {
                    Route::post('/', [MenuController::class, 'index']);
                    Route::post('/generate', [MenuController::class, 'generate']);
                });
            });
        });
    });
});
