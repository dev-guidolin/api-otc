<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [\App\Http\Controllers\ApiController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\ApiController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout/{user}', [\App\Http\Controllers\ApiController::class, 'logoutAll']);
});

Route::post('/webhooks/fnx-pay', [\App\Http\Controllers\Webhooks\WebhooksController::class, 'fnxPay']);

require __DIR__.'/admin.php';
require __DIR__.'/customer.php';
