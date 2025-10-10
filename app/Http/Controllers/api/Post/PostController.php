<?php

namespace App\Http\Controllers\api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\Post\PostService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

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
