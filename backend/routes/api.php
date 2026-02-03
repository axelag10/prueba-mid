<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\VariantController;
use App\Http\Controllers\Api\VariantTypeController;
use App\Http\Controllers\Api\ProductController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum','role:Admin'])->group(function () {
    // Metodos para Admin
    Route::apiResource('users', UserController::class);

    Route::post('users/{user}/roles', [UserController::class, 'assignRole']);

    Route::apiResource('variant-types', VariantTypeController::class)->except(['index', 'show']);
    Route::patch('variant-types/{id}/restore', [VariantTypeController::class, 'restore']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('categories', CategoryController::class);
    Route::patch('categories/{id}/restore', [CategoryController::class, 'restore']);

    Route::apiResource('providers', ProviderController::class);
    Route::patch('providers/{id}/restore', [ProviderController::class, 'restore']);

    Route::apiResource('variants', VariantController::class);
    Route::patch('variants/{id}/restore', [VariantController::class, 'restore']);

    Route::apiResource('products', ProductController::class);
    Route::patch('products/{id}/restore', [ProductController::class, 'restore']);
});
