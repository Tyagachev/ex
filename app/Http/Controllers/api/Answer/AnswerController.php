<?php

namespace App\Http\Controllers\api\Answer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comments\CommentResource;
use App\Models\Comment;
use App\Models\User;
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
        $user = Auth::user();
        $comments = Comment::query()
            ->where('user_id', '=', $user->id)
            ->whereNotNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return CommentResource::collection($comments);
    }

    /**
     * Получение собственных ответов на посты
     * @return AnonymousResourceCollection
     */
    public function posts(): AnonymousResourceCollection
    {
        $user = Auth::user();
        $comments = Comment::query()
            ->where('user_id', '=', $user->id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return CommentResource::collection($comments);
    }
}
