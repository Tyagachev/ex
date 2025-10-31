<?php

use App\Http\Controllers\api\Comment\CommentController;
use Illuminate\Support\Facades\Route;

//COMMENTS
Route::resource('comments', CommentController::class)->middleware('auth:api_token');
Route::get('comments/{comment}', [CommentController::class, 'show']);
Route::get('comments/text/{comment}', [CommentController::class, 'getCommentText']);
