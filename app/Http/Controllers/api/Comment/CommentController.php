<?php

namespace App\Http\Controllers\api\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\Comment\CommentPostResource;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\Comment\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Сохранение комментария
     *
     * @param CommentService $service
     * @param StoreCommentRequest $request
     * @return JsonResponse
     */
    public function store(CommentService $service, StoreCommentRequest $request): JsonResponse
    {
        $request->validated();

        $post = Post::query()->findOrFail($request['postId']);

        $comment = $service->storeComment($post, $request);

        $post = $post->load([
            'user',
            'comments' => function ($query) {
                $query->whereNull('parent_id')
                    ->with(['user', 'replyUser', 'replies']);
            },
        ]);
        return response()->json([
            'post' => $post,
            'commentId' => $comment->id,
            'comments' => CommentResource::collection($post->comments)
        ]);
    }

    /**
     * Получения комментария по id
     *
     * @param Comment $comment
     * @return CommentPostResource
     */
    public function show(Comment $comment)
    {
        $c = Comment::query()->find($comment->id);
        return CommentPostResource::make($c);
    }

    /**
     * Редактирование комментария
     *
     * @param Comment $comment
     * @param CommentService $service
     * @return JsonResponse
     */
    public function edit(Comment $comment, CommentService $service): JsonResponse
    {
        $commentText = $service->editComment($comment);

        return response()->json([
            'text' => $commentText
        ]);
    }

    /**
     * Обновление комментария
     *
     * @param Comment $comment
     * @param Request $request
     * @param CommentService $service
     * @return JsonResponse
     */
    public function update(Comment $comment, CommentService $service, Request $request): JsonResponse
    {
        $updateComment = $service->updateComment($comment, $request);
        if ($updateComment) {
            return response()->json([
                'message' => 'Обновлено',
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Не удалось обновить',
                'status' => 500
            ], 500);
        }
    }

    /**
     * Удаление коммента
     *
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * Получение текста коммента
     * на который ответил пользователь
     *
     */
    public function getCommentText(Comment $comment)
    {
        return Comment::query()
            ->where('id', '=', $comment['parent_id'])
            ->where('user_id', '=', $comment['reply_user_id'])
            ->first('text');
    }

    /**
     * Получение собственных ответов на комментарии
     *
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
     *
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
