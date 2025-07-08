<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'isCustomer'])->prefix('customer')->name('customer.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Customer\CustomerController::class, 'dashboard']);
    Route::post('/new-cob', [\App\Http\Controllers\Customer\CustomerController::class, 'newCob']);

    Route::get('/currency', [\App\Http\Controllers\Customer\CustomerController::class, 'currency']);
    Route::get('/balance', [\App\Http\Controllers\Customer\CustomerController::class, 'balance']);
    Route::get('/system-config', [\App\Http\Controllers\Customer\CustomerController::class, 'systemConfig']);
    Route::get('/deposits', [\App\Http\Controllers\Customer\CustomerController::class, 'deposits']);

    Route::get('/purchases', [\App\Http\Controllers\Customer\CustomerController::class, 'purchases']);

    Route::post('/purchase/new', [\App\Http\Controllers\Customer\CustomerController::class, 'newCoinPurchase']);
    Route::get('/purchases/{market}', [\App\Http\Controllers\Customer\CustomerController::class, 'customerPurchaseRequest']);

    Route::get('/profile/load', [\App\Http\Controllers\Customer\CustomerController::class, 'loadProfile']);
    Route::post('/profile/store', [\App\Http\Controllers\Customer\CustomerController::class, 'storeProfile']);

});
