<?php

//POSTS
use App\Http\Controllers\api\Post\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/show/{post}', [PostController::class, 'show']);
Route::post('/posts', [PostController::class,'store'])->middleware('auth:api_token');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware('auth:api_token');
Route::post('/posts/update', [PostController::class, 'update'])->middleware('auth:api_token');
