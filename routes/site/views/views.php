<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\View\ViewController;

Route::get('views-stories', [ViewController::class, 'index'])->middleware('auth:api_token');
