<?php

namespace App\Http\Controllers\api\Save;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Services\Save\SaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $posts = Post::query()->whereHas('saves', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['user', 'saves' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return PostResource::collection($posts);
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
        $request = $request->all();
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
