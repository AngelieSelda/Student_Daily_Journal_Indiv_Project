<?php

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/journals', function () {
    return Journal::all();
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
