<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', function (Request $request) {
    return response([
        'message' => 'Welcome to the Books API',
    ]);
});

Route::get('/search', [\App\Http\Controllers\BooksController::class, 'search']);

Route::post('/review', [\App\Http\Controllers\ReviewController::class, 'store']);
