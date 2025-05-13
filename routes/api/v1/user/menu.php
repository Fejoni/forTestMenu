<?php

use App\Http\Controllers\Api\v1\User\Menu\MenuController;
use App\Http\Controllers\Api\v1\User\Menu\MenuDishController;
use Illuminate\Support\Facades\Route;

Route::prefix('menu')->group(function () {
    Route::post('/', [MenuController::class, 'index']);

    Route::prefix('generate')->group(function () {
        Route::post('/', [MenuController::class, 'generate']);
        Route::post('/replacement', [MenuController::class, 'replacementGenerate']);
    });

    Route::prefix('dish')->group(function () {
        Route::get('/', [MenuDishController::class, 'index']);
        Route::delete('/delete', [MenuDishController::class, 'delete']);
        Route::post('/replacement', [MenuDishController::class, 'replacement']);
    });
});
