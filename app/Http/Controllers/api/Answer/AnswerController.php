<?php

namespace App\Http\Controllers\api\Answer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comments\CommentReplyResource;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function index()
    {
        $user = Auth::id();
        $comments = Comment::query()
            ->where('user_id', '=', $user)
            ->whereNotNull('parent_id')
            ->paginate(10);
        return CommentReplyResource::collection($comments);
    }
}
