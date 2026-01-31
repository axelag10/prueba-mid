<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProviderController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum','role:Admin'])->group(function () {

    Route::apiResource('categories', CategoryController::class);
    Route::patch('categories/{id}/restore', [CategoryController::class, 'restore']);

    Route::apiResource('providers', ProviderController::class);
    Route::patch('providers/{id}/restore', [ProviderController::class, 'restore']);
});
