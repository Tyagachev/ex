<?php

namespace App\Http\Controllers\api\Save;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\Save\SaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class SaveController extends Controller
{
    /**
     * Вывод сохраненных постов
     *
     * @return AnonymousResourceCollection
     */
    public function savedPosts(): AnonymousResourceCollection
    {
        $user = Auth::user();

        $posts = Post::query()
            ->join('saves', function($join) use ($user) {
                $join->on('posts.id', '=', 'saves.saveable_id')
                    ->where('saves.saveable_type', '=', Post::class)
                    ->where('saves.user_id', '=', $user->id);
            })
            ->with([
                'user',
                'saves' => function($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            }])
            ->select('posts.*')
            ->orderBy('saves.created_at', 'desc')
            ->paginate(10);

        return PostResource::collection($posts);
    }

    /**
     * Вывод сохраненных комментариев
     *
     * @return AnonymousResourceCollection
     */
    public function savedComments(): AnonymousResourceCollection
    {
        $user = Auth::user();

        $comments = Comment::query()
            ->join('saves', function($join) use ($user) {
                $join->on('comments.id', '=', 'saves.saveable_id')
                    ->where('saves.saveable_type', '=', Comment::class)
                    ->where('saves.user_id', '=', $user->id);
            })
            ->with([
                'user',
                'saves' => function($query) use ($user) {
                    $query->where('user_id', '=', $user->id);
                }])
            ->select('comments.*')
            ->orderBy('saves.created_at', 'desc')
            ->paginate(10);

        return CommentResource::collection($comments);
    }

    /**
     * Сохранение комментария или поста
     *
     * @param Request $request
     * @param SaveService $service
     * @return JsonResponse
     */
    public function store(Request $request, SaveService $service): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:post,comment'
        ]);

        $saved = $service->store($request);
        return response()->json([
            'saved' => $saved
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
