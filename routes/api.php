<?php

use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/blogs', PostController::class);
    Route::get("my-blogs", [PostController::class, 'myBlogs']);
    Route::get("category-blogs/{categoryId}", [PostController::class, 'categoryBlogs']);
    Route::apiResource('categories', \App\Http\Controllers\Api\CategoryController::class);
});