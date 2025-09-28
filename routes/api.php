<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::prefix('product')->group(function () {
    Route::get('/ping', [ProductController::class, 'ping']);   // /api/product/ping

});
