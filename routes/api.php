<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\JournalController;
use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\UserJournalsController;
use Illuminate\Support\Facades\Route;

// Public routes: No token needed to register or login
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes: Require a valid JWT token
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    // Journal CRUD routes
    Route::apiResource('journals', JournalController::class);
    Route::put('journals/{journal}', [JournalController::class, 'replace']);

    // Author routes (read-only)
    Route::apiResource('authors', AuthorsController::class)->only(['index', 'show']);

    // Nested: journals by user (apiResource gamit ang scoped())
    Route::apiResource('users.journals', UserJournalsController::class)
        ->scoped();

    // PUT and PATCH use individual routes, kaya ->scopeBindings() ang gamitin
    Route::put('users/{user}/journals/{journal}', [UserJournalsController::class, 'replace'])
        ->scopeBindings();
        
    Route::patch('users/{user}/journals/{journal}', [UserJournalsController::class, 'update'])
        ->scopeBindings();
});
