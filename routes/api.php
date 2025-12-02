<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

require_once('site/auth/auth.php');
require_once('site/posts/posts.php');
require_once('site/comments/comments.php');
require_once('site/answers/answers.php');
require_once('site/liked/liked.php');
require_once('site/votes/votes.php');
require_once('site/saves/saves.php');
require_once('site/shares/share.php');
require_once('site/views/views.php');
require_once('site/topics/topics.php');

Route::resource('tests', TestController::class)->middleware('auth:api_token');
