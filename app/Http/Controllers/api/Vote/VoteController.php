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
            'Posts' => PostResource::collection($posts)->resolve()
        ]);
    }

    /**
     * Запись голоса
     *
     * @param Request $request
     * @param VoteService $service
     * @return JsonResponse
     */
    public function store(Request $request, VoteService $service): JsonResponse
    {
        $request = $request->all();
        $item = $service->voteStore($request);

        return response()->json([
            'status' => 200,
            'totalVotes' => $item->totalVotes(),
            'votes' => $item->votes,
        ]);
    }
}
