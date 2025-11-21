<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Liked\LikedController;

Route::middleware('auth:api_token')->group( function () {
    Route::get('liked/posts/up', [LikedController::class,'likedPostsUp']);
    Route::get('liked/posts/down', [LikedController::class,'likedPostsDown']);
    Route::get('liked/comments/up', [LikedController::class,'likedCommentsUp']);
    Route::get('liked/comments/down', [LikedController::class,'likedCommentsDown']);
});

