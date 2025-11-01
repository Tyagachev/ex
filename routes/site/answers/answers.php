<?php

use App\Http\Controllers\api\Answer\AnswerController;
use Illuminate\Support\Facades\Route;

Route::resource('answers', AnswerController::class);
