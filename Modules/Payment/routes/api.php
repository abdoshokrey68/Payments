<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\Api\PaymentController;

Route::middleware(['auth:api'])->prefix('payments')->controller(PaymentController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'pay');
});
