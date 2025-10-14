<?php

namespace App\Http\Controllers\api\Vote;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Models\Vote;
use App\Services\Vote\VoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    /**
     * Обновление голосов
     * на главной странице
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::with('user')
            ->orderBy('created_at','desc')
            ->get();

        return response()->json([
            'posts' => PostResource::collection($posts)->resolve()
        ]);
    }

    /**
     * Запись голоса
     *
     * @param Request $request
     * @param VoteService $service
     */
    public function store(Request $request, VoteService $service)
    {
        $request = $request->all();
        $service->voteStore($request);
        return response()->json(['status' => 200, 'user' => Auth::user()]);
        /*return response()->json([
            'status' => 200,
            'totalVotes' => $result->totalVotes(),
            'obj' => !count($result->votes) ? null : $result->votes
        ]);*/
    }
    public function postVote(Post $post)
    {
        if (!Auth::check()) {
            return null;
        }

        $auth = Auth::user();

        $type = 'Post';
        $vote = Vote::query()->where('voteable_id', '=', $post->id)
            ->where('user_id', '=', $auth->id)
            ->where('voteable_type', 'LIKE', "%{$type}%")
            ->first();
        return $vote;
    }
}
