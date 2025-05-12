<?php

use App\Http\Controllers\Api\v1\Menu\MenuController;
use App\Http\Controllers\Api\v1\Menu\MenuFoodController;
use Illuminate\Support\Facades\Route;

Route::prefix('menu')->group(function () {
    Route::post('/', [MenuController::class, 'index']);
    Route::post('/generate', [MenuController::class, 'generate']);
    Route::post('/repeat/generate', [MenuController::class, 'repeatGenerate']);

    Route::prefix('food')->group(function () {
        Route::delete('/delete', [MenuFoodController::class, 'delete']);
        Route::post('/repeat', [MenuFoodController::class, 'repeat']);
    });
});
