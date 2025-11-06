<?php

namespace App\Http\Controllers\api\Answer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comments\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * Получение собственных ответов на комментарии
     * @return AnonymousResourceCollection
     */
    public function replies(): AnonymousResourceCollection
    {
        $user = Auth::id();
        $comments = Comment::query()
            ->where('user_id', '=', $user)
            ->whereNotNull('parent_id')
            ->paginate(10);
        return CommentResource::collection($comments);
    }

    /**
     * Получение собственных ответов на посты
     * @return AnonymousResourceCollection
     */
    public function posts(): AnonymousResourceCollection
    {
        $user = Auth::id();
        $comments = Comment::query()
            ->where('user_id', '=', $user)
            ->whereNull('parent_id')
            ->paginate(10);
        return CommentResource::collection($comments);
    }
}
