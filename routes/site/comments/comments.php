<?php

use App\Http\Controllers\api\Comment\CommentController;
use Illuminate\Support\Facades\Route;

//COMMENTS
Route::get('comments', [CommentController::class, 'index']);
Route::middleware('auth:api_token')->group( function () {
    Route::post('comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'destroy']);
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit']);
    Route::patch('comments/{comment}', [CommentController::class, 'update']);
    Route::get('comments/replies', [CommentController::class, 'replies']);
    Route::get('comments/posts', [CommentController::class, 'posts']);
});

Route::get('comments/{comment}', [CommentController::class, 'show']);
Route::get('comments/text/{comment}', [CommentController::class, 'getCommentText']);

