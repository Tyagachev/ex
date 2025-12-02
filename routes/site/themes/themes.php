<?php

use App\Http\Controllers\api\Theme\ThemeController;
use Illuminate\Support\Facades\Route;

//THEMES
Route::resource('themes', ThemeController::class)->middleware('auth:api_token');
