<?php

namespace App\Http\Controllers\api\Post;

use App\Events\Post\PostViewCount;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Resources\Comments\CommentResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\Post\PostService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::with('user')
            ->orderBy('created_at','desc')
            ->get();

        $postsResource =  PostResource::collection($posts);

        return response()->json($postsResource);
    }


    /**
     * Создание поста
     *
     * @param StorePostRequest $request
     * @param PostService $service
     * @return \Illuminate\Http\JsonResponse|RedirectResponse
     * @throws AuthorizationException
     */
    public function store(StorePostRequest $request, PostService $service): JsonResponse|RedirectResponse
    {
        $this->authorize('create', Post::class);
        $data = $request->validated();
        $imageCount = collect($data['blocks'])->where('type', 'image')->count();
        if ($imageCount > 10) {
            return response()->json(['error' => 'Максимум 10 изображений']);
        }

        try {
            $service->storePost($data, $request);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Ошибка загрузки: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 200]);

    }

    /**
     * Просмотр поста
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post): JsonResponse
    {

        event(new PostViewCount($post));

        $post = $post->load([
            'user',
            'comments' => function ($query) {
                $query->whereNull('parent_id')
                    ->with(['user', 'replyUser', 'replies']);
            },
        ]);

        $commentsCount = Comment::query()
            ->where('post_id', '=', $post->id)
            ->get()
            ->count();

        return response()->json([
                'id' => $post->id,
                'userId' => $post->user_id,
                'title' => $post->title,
                'content' => $post->content,
                'viewCount' => $post->view,
                'createdAtHuman' => $post->created_at_human,
                'updatedAtHuman' => $post->updated_at_human,
                'user' => UserResource::make($post->user),
                'comments' => CommentResource::collection($post->comments),
                'commentsCount' => $commentsCount,
                'totalVotes' => $post->totalVotes(),
                'votes' => $post->votes,
                'blocks' => $post->blocks,
                'shareCount' => $post->share_count
        ]);
    }

    /**
     * Удаление поста
     * и связанных с ним комментов
     *
     * @param Post $post
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $id = Auth::id();

        foreach ($post->blocks as $item) {
            if ($item['type'] === 'image' && !empty($item['path'])) {
                $fileName = basename($item['path']);
                Storage::disk('s3')->delete("users/{$id}/posts/{$fileName}");
            }
        }

        //Comment::where('post_id', $post->id)->delete();

        $post->delete();

        return response()->json(['status' => 200]);
    }
}
