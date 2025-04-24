<?php

use App\Http\Controllers\Api\v1\Product\Category\CategoryController;
use App\Http\Controllers\Api\v1\Product\Division\DivisionController;
use App\Http\Controllers\Api\v1\Product\ProductController;
use App\Http\Controllers\Api\v1\Product\Shop\ShopController;
use App\Http\Controllers\Api\v1\Product\Unit\UnitController;
use Illuminate\Support\Facades\Route;


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::delete('/', [ProductController::class, 'delete']);
    Route::patch('/', [ProductController::class, 'update']);
    Route::post('/', [ProductController::class, 'store']);

    Route::prefix('unit')->group(function () {
        Route::get('/', [UnitController::class, 'index']);
        Route::delete('/', [UnitController::class, 'delete']);
        Route::patch('/', [UnitController::class, 'update']);
        Route::post('/', [UnitController::class, 'store']);
    });

    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::delete('/', [CategoryController::class, 'delete']);
        Route::patch('/', [CategoryController::class, 'update']);
        Route::post('/', [CategoryController::class, 'store']);
    });

    Route::prefix('shop')->group(function () {
        Route::get('/', [ShopController::class, 'index']);
        Route::delete('/', [ShopController::class, 'delete']);
        Route::patch('/', [ShopController::class, 'update']);
        Route::post('/', [ShopController::class, 'store']);
    });

    Route::prefix('division')->group(function () {
        Route::get('/', [DivisionController::class, 'index']);
        Route::delete('/', [DivisionController::class, 'delete']);
        Route::patch('/', [DivisionController::class, 'update']);
        Route::post('/', [DivisionController::class, 'store']);
    });

    Route::prefix('division')->group(function () {
        Route::get('/', [DivisionController::class, 'index']);
        Route::delete('/', [DivisionController::class, 'delete']);
        Route::patch('/', [DivisionController::class, 'update']);
        Route::post('/', [DivisionController::class, 'store']);
    });
});
