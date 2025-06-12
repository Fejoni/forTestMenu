<?php

use App\Http\Controllers\Api\v1\Admin\Dish\DishCategoryController;
use App\Http\Controllers\Api\v1\Admin\Dish\DishController;
use App\Http\Controllers\Api\v1\Admin\Dish\DishSuitableController;
use App\Http\Controllers\Api\v1\Admin\Dish\DishTimeController;
use App\Http\Controllers\Api\v1\Admin\Dish\DishTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('dish')->group(function () {
    Route::prefix('category')->group(function () {
        Route::get('/', [DishCategoryController::class, 'index']);
        Route::patch('/', [DishCategoryController::class, 'update']);
        Route::post('/', [DishCategoryController::class, 'store']);
        Route::delete('/', [DishCategoryController::class, 'destroy']);
    });

    Route::prefix('suitable')->group(function () {
        Route::get('/', [DishSuitableController::class, 'index']);
        Route::patch('/', [DishSuitableController::class, 'update']);
        Route::post('/', [DishSuitableController::class, 'store']);
        Route::delete('/', [DishSuitableController::class, 'destroy']);
    });

    Route::prefix('time')->group(function () {
        Route::get('/', [DishTimeController::class, 'index']);
        Route::patch('/', [DishTimeController::class, 'update']);
        Route::post('/', [DishTimeController::class, 'store']);
        Route::delete('/', [DishTimeController::class, 'destroy']);
    });

    Route::prefix('type')->group(function () {
        Route::get('/', [DishTypeController::class, 'index']);
        Route::patch('/', [DishTypeController::class, 'update']);
        Route::post('/', [DishTypeController::class, 'store']);
        Route::delete('/', [DishTypeController::class, 'destroy']);
    });

    Route::get('/', [DishController::class, 'index']);
    Route::post('/{dish}', [DishController::class, 'update']);
    Route::post('/', [DishController::class, 'store']);
    Route::delete('/', [DishController::class, 'destroy']);
});
