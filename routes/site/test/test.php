<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::resource('tests', TestController::class)->middleware('auth:api_token');
