<?php

use App\Http\Controllers\Api\v1\User\Purchases\PurchasesController;
use Illuminate\Support\Facades\Route;

Route::prefix('purchases')->group(function () {
    Route::get('/', [PurchasesController::class, 'index']);

    Route::get('/products', [PurchasesController::class, 'products']);
    Route::patch('/', [PurchasesController::class, 'storeProduct']);

    Route::post('/accept', [PurchasesController::class, 'acceptPurchase']);
    Route::post('/remove', [PurchasesController::class, 'removePurchase']);
    Route::post('/update', [PurchasesController::class, 'updatePurchase']);
    Route::delete('/clear', [PurchasesController::class, 'clear']);
});
