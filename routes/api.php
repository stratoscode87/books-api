<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', function (Request $request) {
    return response([
        'message' => 'Welcome to the Books API',
    ]);
});

/*
 * Book routes
 */
Route::get('/search', [\App\Http\Controllers\BooksController::class, 'search']);

/*
 * Review routes
 */
Route::get('/review/{id}', [\App\Http\Controllers\ReviewController::class, 'review']);
Route::post('/review', [\App\Http\Controllers\ReviewController::class, 'store']);
