<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\JournalController;
use Illuminate\Support\Facades\Route;

// Public routes: Walang token na kailangan para makapag-register o mag-login
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes: Kailangan ng valid JWT token
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    // Journal routes na protektado na rin ng authentication
    Route::apiResource('journals', JournalController::class)->only(['index']);
});