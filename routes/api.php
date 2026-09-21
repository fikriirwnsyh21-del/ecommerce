<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes - PasarKeren Marketplace (v1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // 1. Authentication (Public)
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    // 2. Catalog & Discovery (Public)
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{slugOrId}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);

    // 3. Authenticated Customer & Seller Routes (auth:sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth management
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Cart
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/add', [CartController::class, 'add']);
        Route::put('/cart/{id}', [CartController::class, 'update']);
        Route::delete('/cart/{id}', [CartController::class, 'destroy']);

        // Checkout
        Route::post('/checkout', [CheckoutController::class, 'process']);

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders/{id}/pay', [OrderController::class, 'pay']);
        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    });
});
