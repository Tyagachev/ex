<?php

namespace App\Http\Controllers\api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')
            ->orderBy('created_at','desc')
            ->get();

        $postsResource =  PostResource::collection($posts);
        
        return response()->json($postsResource);
    }
}
