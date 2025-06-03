<?php

use App\Http\Controllers\Api\v1\Admin\Dish\DishCategoryController;
use App\Http\Controllers\Api\v1\Admin\Dish\DishTimeController;
use App\Http\Controllers\Api\v1\Admin\Image\ImageUploadController;
use App\Http\Controllers\Api\v1\Admin\Product\Category\CategoryController;
use App\Http\Controllers\Api\v1\Admin\Product\Division\DivisionController;
use App\Http\Controllers\Api\v1\User\Dish\DishController;
use App\Http\Controllers\Api\v1\User\Product\ProductController;
use App\Http\Controllers\Api\v1\User\Recipes\RecipesController;
use App\Http\Controllers\Api\v1\User\User\UserCheckoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::post('login', [UserCheckoutController::class, 'login']);
    Route::post('register', [UserCheckoutController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('checkout', [UserCheckoutController::class, 'checkout']);

        require_once __DIR__ . '/profile.php';
        require_once __DIR__ . '/menu.php';
        require_once __DIR__ . '/purchases.php';

        Route::prefix('dish')->group(function () {
            Route::prefix('time')->group(function () {
                Route::get('/', [DishController::class, 'time']);
                Route::get('/default', [DishController::class, 'timeDefaultSelect']);
            });
            Route::get('/categories', [DishCategoryController::class, 'index']);
        });

        Route::prefix('recipes')->group(function () {
            Route::post('/filter', [RecipesController::class, 'filter']);
            Route::get('/products', [RecipesController::class, 'products']);
        });

        Route::prefix('products')->group(function () {
            Route::get('/list', [ProductController::class, 'list']);
            Route::post('/create', [ProductController::class, 'create']);
            Route::post('/update', [ProductController::class, 'update']);
            Route::delete('/delete', [ProductController::class, 'delete']);
            Route::get('/divisions', [DivisionController::class, 'index']);
            Route::get('/categories', [CategoryController::class, 'index']);
        });

        Route::prefix('dish')->group(function () {
            Route::post('/create', [DishController::class, 'create']);
            Route::post('/update', [DishController::class, 'update']);
            Route::delete('/delete', [DishController::class, 'delete']);
        });

        Route::prefix('file')->group(function () {
            Route::post('upload', [ImageUploadController::class, 'upload']);
        });



    });

    require_once __DIR__ . '/telegram.php';
});
