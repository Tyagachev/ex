<?php

use Illuminate\Support\Facades\Route;

Route::get('{page}', [App\Http\Controllers\IndexController::class, 'index'])->where('page', '.*');
