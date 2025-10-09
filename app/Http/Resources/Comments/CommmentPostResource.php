<?php

namespace App\Http\Resources\Comments;

use App\Http\Resources\Post\PostWithoutCommentsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommmentPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->user['name'],
            'text' => $this->text,
            'post' => PostWithoutCommentsResource::make($this->post),
            'createdAtHuman' => $this->created_at->diffForHumans(),
            'updatedAtHuman' => $this->updated_at->diffForHumans(),
        ];
    }
}
