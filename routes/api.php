<?php

require('auth.php');

use App\Http\Controllers\api\Auth\Login\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Post\PostController;



Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);


//POSTS
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class,'store'])->middleware('auth:api_token');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware('auth:api_token');
