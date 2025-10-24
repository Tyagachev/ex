<?php

use App\Http\Controllers\api\Comment\CommentController;
use Illuminate\Support\Facades\Route;

//COMMENTS
Route::resource('comments', CommentController::class)->middleware('auth:api_token');
Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth:api_token');
Route::post('/comments/text', [CommentController::class, 'getCommentText']);
