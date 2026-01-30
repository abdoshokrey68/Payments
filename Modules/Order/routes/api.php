<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware(['auth:api'])->prefix('orders')->controller(OrderController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::put('/confirm/{id}', 'confirm');
    Route::put('/cancel/{id}', 'cancel');
    Route::delete('/{id}', 'destroy');
});
