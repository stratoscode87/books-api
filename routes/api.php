<?php

use App\Http\Controllers\BooksController;
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
Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/search', [BooksController::class, 'search']);
});

/*
 * Review routes
 */
Route::group([
    'prefix' => 'review',
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/{id}', [ReviewController::class, 'review']);
    Route::post('/', [ReviewController::class, 'store']);
    Route::put('/{id}', [ReviewController::class, 'update']);
    Route::delete('/{id}', [ReviewController::class, 'destroy']);
});
