<?php

namespace App\Http\Resources\V1;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\JournalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\UserJournalsController;

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

// Sa loob ng iyong Route::middleware('auth:api')->group(function () { ... })
Route::apiResource('journals', JournalController::class)->only(['index', 'show']);

Route::apiResource('authors', AuthorsController::class)->only(['index', 'show']);

Route::apiResource('users.journals', UserJournalsController::class)->only(['index']);