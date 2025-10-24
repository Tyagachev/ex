<?php

use App\Http\Controllers\api\Share\ShareController;
use Illuminate\Support\Facades\Route;

//SHARE
Route::resource('/shares', ShareController::class);
