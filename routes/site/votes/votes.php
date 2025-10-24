<?php


//VOTES
use App\Http\Controllers\api\Vote\VoteController;
use Illuminate\Support\Facades\Route;

Route::resource('/votes', VoteController::class)->middleware('auth:api_token');
Route::get('/votes/{post}/vote', [VoteController::class, 'postVote']);
