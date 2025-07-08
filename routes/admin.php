<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard']);

    // Deposits
    Route::get('/deposits', [\App\Http\Controllers\Admin\AdminController::class, 'deposits']);

    // Customers
    Route::get('/customers', [\App\Http\Controllers\Admin\AdminController::class, 'customers']);
    Route::put('/customers/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'customerUpdate']);

    Route::get('/customer/profile/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'customerProfile']);
    Route::post('/customer/profile/{id}/update', [\App\Http\Controllers\Admin\AdminController::class, 'customerProfileUpdate']);

    // Transactions
    Route::get('/transactions', [\App\Http\Controllers\Admin\AdminController::class, 'transactions']);
    Route::get('/transactions/pending', [\App\Http\Controllers\Admin\AdminController::class, 'transactionsPending']);
    Route::put('/transactions/{purchase}', [\App\Http\Controllers\Admin\AdminController::class, 'transactionUpdate']);

    // Config
    Route::get('/system-config', [\App\Http\Controllers\Admin\AdminController::class, 'config']);
    Route::put('/system-config', [\App\Http\Controllers\Admin\AdminController::class, 'configUpdate']);

});
