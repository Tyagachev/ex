<?php

namespace App\Http\Controllers\api\Post;

use App\Events\Post\PostViewCount;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\Post\PostService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $posts = Post::with('user')
            ->orderBy('created_at','desc')->paginate(10);

        return PostResource::collection($posts);
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
            return response()->json(['error' => 'Ошибка загрузки: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 200]);
    }

    /**
     * Обновление поста
     *
     * @param UpdatePostRequest $request
     * @param PostService $service
     * @return JsonResponse|RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdatePostRequest $request, PostService $service): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        $post = Post::query()->find($data['postId']);

        $this->authorize('update', $post);

        $imageCount = collect($data['blocks'])->where('type', 'image')->count();

        if ($imageCount > 10) {
            return response()->json(['blocks' => 'Максимум 10 изображений']);
        }

        try {
            $service->updatePost($data, $request, $post);
            return response()->json(['status' => 200]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ошибка обновления: ' . $e->getMessage()]);
        }
    }


    /**
     * Просмотр поста
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        $user = null;
        $token = request()->bearerToken();

        if ($token) {
            $user = User::query()->where('api_token', $token)->first();
            if ($user) {
                auth()->setUser($user);
            }
        }

        event(new PostViewCount($post, $user));

        $post = $post->load([
            'user',
            'views',
            'comments' => function ($query) {
                $query->whereNull('parent_id')
                    ->with(['user', 'replyUser', 'replies']);
            },
        ]);
        return PostResource::make($post);
    }

    /**
     * Удаление поста
     * и связанных с ним комментов
     *
     * @param Post $post
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        $id = Auth::id();

        foreach ($post->blocks as $item) {
            if ($item['type'] === 'image' && !empty($item['path'])) {
                $fileName = basename($item['path']);
                Storage::disk('s3')->delete("users/{$id}/posts/{$fileName}");
            }
        }

        Comment::query()->where('post_id', $post->id)->delete();

        $post->delete();

        return response()->json(['status' => 200]);
    }

    /**
     * Получение собственных постов
     *
     * @return AnonymousResourceCollection
     */
    public function getUserPosts(): AnonymousResourceCollection
    {
        $user = Auth::id();
        $posts = Post::query()->with('user')
            ->where('user_id', $user)
            ->orderBy('created_at','desc')->paginate(10);

        return PostResource::collection($posts);
    }

}
