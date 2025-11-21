<?php

namespace App\Http\Resources\Comments;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentPostResource extends JsonResource
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
            'text' => $this->text,
            'postId' => $this->post_id,
            'post' => $this->post,
            'parent' => $this->parent_id,
            'user' => new UserResource($this->user),
            'reply_user' => $this->replyUser ? new UserResource($this->replyUser) : null,
            'replies' => CommentPostResource::collection($this->replies),
            'votes' => count($this->votes) ? $this->votes : [0],
            'saves' => $this->saves,
            'totalVotes' => count($this->votes),
            'shareCount' => $this->share_count,
            'createdAtHuman' => $this->created_at->diffForHumans(),
            'updatedAtHuman' => $this->updated_at->diffForHumans(),
        ];
    }
}
