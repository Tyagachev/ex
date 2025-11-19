<?php

namespace App\Http\Controllers\api\Liked;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class LikedController extends Controller
{

    /**
     * Вывод постов за который пользователь
     * поставил лайк
     *
     * @return AnonymousResourceCollection
     */
    public function up(): AnonymousResourceCollection
    {
        $user = Auth::user();
        $posts = Post::query()->whereHas('votes', function($query) {
            $query->where('vote', '=', 1);
        })
            ->with('user')->whereNot('user_id', $user->id)
            ->paginate(10);
        return PostResource::collection($posts);
    }

    /**
     * Вывод постов за который пользователь
     * поставил дизлайк
     *
     * @return AnonymousResourceCollection
     */
    public function down(): AnonymousResourceCollection
    {
        $user = Auth::user();
        $posts = Post::query()->whereHas('votes', function($query) {
            $query->where('vote','<', 0);
        })
            ->with('user')->whereNot('user_id', $user->id)
            ->paginate(10);
        return PostResource::collection($posts);
    }
}
