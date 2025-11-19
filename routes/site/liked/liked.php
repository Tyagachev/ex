<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Liked\LikedController;

Route::get('liked/up', [LikedController::class,'up'])->middleware('auth:api_token');
Route::get('liked/down', [LikedController::class,'down'])->middleware('auth:api_token');
