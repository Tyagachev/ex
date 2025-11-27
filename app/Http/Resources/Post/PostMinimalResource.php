<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostMinimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'title' => $this->title,
            'content' => $this->content,
            'user' => UserResource::make($this->user),
            'viewCount' => count($this->views),
            'commentsCount' => $this->totalComments(),
            'totalVotes' => $this->totalVotes(),
            'votes' => count($this->votes) ? $this->votes : [0],
            'saves' => $this->saves,
            'blocks' => $this->blocks,
            'shareCount' => $this->share_count,
            'createdAtHuman' => $this->created_at->diffForHumans(),
            'updatedAtHuman' => $this->updated_at->diffForHumans(),
        ];
    }
}
