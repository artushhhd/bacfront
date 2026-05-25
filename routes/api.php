<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, ProductsController, AdminController, LikeController};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/products', [ProductsController::class, 'index']);
Route::get('/categories', [ProductsController::class, 'categories']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', fn(\Illuminate\Http\Request $request) => $request->user());

    Route::post('/products', [ProductsController::class, 'store']);
    Route::delete('/products/{id}', [ProductsController::class, 'destroy']);
    Route::post('/products/{id}/like', [LikeController::class, 'toggle']);

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'dashboard']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::delete('/products/{id}', [AdminController::class, 'deleteProduct']);
    });
});
