<?php

use App\Http\Controllers\Api\V1\JournalController;
use Illuminate\Support\Facades\Route;

// apiResource creates standard REST routes; only(['index']) keeps just what we need now.
Route::apiResource('journals', JournalController::class)->only(['index']);