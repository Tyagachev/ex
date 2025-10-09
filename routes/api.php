<?php

//require('auth.php');

use App\Http\Controllers\api\Auth\Login\LoginController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Post\PostController;

Route::get('/posts', [PostController::class, 'index']);

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::middleware('auth:api_token')->get('/user', [UserController::class, 'me']);
