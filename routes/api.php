<?php

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', function (Request $request) {
    return response([
        'message' => 'Welcome to the Books API',
    ]);
});

/*
 * Auth routes
 */
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');

/*
 * Book routes
 */
Route::get('/search', [\App\Http\Controllers\BooksController::class, 'search']);

/*
 * Review routes
 */
Route::get('/review/{id}', [ReviewController::class, 'review']);
Route::post('/review', [ReviewController::class, 'store']);
Route::put('/review/{id}', [ReviewController::class, 'put']);
