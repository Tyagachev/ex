<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

require_once('site/auth/auth.php');
require_once('site/posts/posts.php');
require_once('site/comments/comments.php');
require_once('site/votes/votes.php');
require_once('site/shares/share.php');

Route::resource('tests', TestController::class);
