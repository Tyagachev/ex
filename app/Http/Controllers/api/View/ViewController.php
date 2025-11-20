<?php

namespace App\Http\Controllers\api\View;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ViewController extends Controller
{

    /**
     * Вывод просмотренных постов по дате последнего просмотра
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $user = auth()->user();
        $posts = Post::query()->whereHas('views', function($query) use ($user) {
            $query->where('user_id', '=', $user->id);
        })
            ->with(['user', 'views' => function($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            }])
            ->join('views', 'posts.id', '=', 'views.post_id')->where('views.user_id', $user->id)
            ->select('posts.*')
            ->orderByDesc('views.updated_at')
            ->paginate(10);

        return PostResource::collection($posts);
    }
}
