<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', function (Request $request) {
        return [
            'user' => $request->user(),
            'roles' => $request->user()->getRoleNames()
        ];
    });

    // Route::middleware('role:Admin')->get('/admin-test', function () {
    //     return response()->json(['ok' => true]);
    // });

});

Route::get('/admin-test', function () {
    return response()->json(['ok' => true]);
})->middleware(['auth:sanctum','role:Admin']);
