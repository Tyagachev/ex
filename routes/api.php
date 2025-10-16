<?php

require_once('auth.php');

use App\Http\Controllers\api\Auth\Login\LoginController;
use App\Http\Controllers\api\Comment\CommentController;
use App\Http\Controllers\api\Share\ShareController;
use App\Http\Controllers\api\Vote\VoteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Post\PostController;



Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);


//POSTS
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class,'store'])->middleware('auth:api_token');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware('auth:api_token');
Route::get('/posts/show/{post}', [PostController::class, 'show']);

//COMMENTS
Route::resource('comments', CommentController::class)->middleware('auth:api_token');
Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth:api_token');
Route::post('/comments/text', [CommentController::class, 'getCommentText']);

//VOTES
Route::resource('/votes', VoteController::class)->middleware('auth:api_token');
Route::get('/votes/{post}/vote', [VoteController::class, 'postVote']);

//SHARE
Route::resource('/share', ShareController::class);
