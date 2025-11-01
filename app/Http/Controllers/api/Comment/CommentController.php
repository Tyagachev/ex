<?php

namespace App\Http\Controllers\api\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\Comments\CommentResource;
use App\Http\Resources\Comments\CommentUserResource;use App\Models\Comment;
use App\Models\Post;
use App\Services\Comment\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return CommentUserResource::collection($user->comments);
    }
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

    public function show(Comment $comment)
    {
        return CommentResource::make($comment);
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
}
