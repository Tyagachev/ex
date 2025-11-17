<?php

use App\Http\Controllers\api\Save\SaveController;
use Illuminate\Support\Facades\Route;
Route::get('saves', [SaveController::class, 'savedPosts'])->middleware('auth:api_token');
Route::get('saves/comments', [SaveController::class, 'savedComments'])->middleware('auth:api_token');
Route::post('saves', [SaveController::class, 'store'])->middleware('auth:api_token');
