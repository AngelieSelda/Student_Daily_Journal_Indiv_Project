<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => (new class {
    use \App\Traits\ApiResponses;

    public function run()
    {
        return $this->ok('API is working');
    }
})->run());

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
