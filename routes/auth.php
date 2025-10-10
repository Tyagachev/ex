<?php

use App\Http\Controllers\api\Auth\Login\LoginController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api_token')->get('/user', [UserController::class, 'me']);

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
