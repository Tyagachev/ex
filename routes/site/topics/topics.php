<?php

use App\Http\Controllers\api\Topic\TopicController;
use Illuminate\Support\Facades\Route;

//TOPICS
Route::resource('topics', TopicController::class)->middleware('auth:api_token');
