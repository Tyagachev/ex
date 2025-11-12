<?php

use App\Http\Controllers\api\Save\SaveController;
use Illuminate\Support\Facades\Route;

Route::resource('saves', SaveController::class)->middleware('auth:api_token');
