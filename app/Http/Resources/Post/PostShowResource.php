<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Comments\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $comments = CommentResource::collection($this->comments);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'user' => $this->user,
            'comments' => $comments->resolve(),
            'createdAtHuman' => $this->created_at->diffForHumans(),
        ];
    }
}
