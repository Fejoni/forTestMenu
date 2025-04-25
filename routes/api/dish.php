<?php

use App\Http\Controllers\Api\v1\Dish\DishCategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('dish')->group(function () {
    Route::prefix('category')->group(function (){
        Route::get('/', [DishCategoryController::class, 'index']);
        Route::patch('/', [DishCategoryController::class, 'update']);
        Route::post('/', [DishCategoryController::class, 'store']);
        Route::delete('/', [DishCategoryController::class, 'destroy']);
    });
});
