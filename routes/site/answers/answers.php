<?php

use App\Http\Controllers\api\Answer\AnswerController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api_token')->group( function () {
    Route::get('answers/replies', [AnswerController::class, 'replies']);
    Route::get('answers/posts', [AnswerController::class, 'posts']);
});
