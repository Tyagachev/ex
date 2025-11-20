<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Comments\CommentResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function imgCount($blocks)
    {
        $sum = [];
        if (is_array($blocks)) {
            foreach ($blocks as $block) {
                if ($block['type'] === 'image') {
                    $sum[] = $block;
                }
            }
            return count($sum);
        }
        return null;
    }
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
            'viewCount' => count($this->views),
            'user' => UserResource::make($this->user),
            'comments' => CommentResource::collection($this->comments),
            'commentsCount' => count($this->comments),
            'votes' => count($this->votes) ? $this->votes : [0],
            'saves' => $this->saves,
            'totalVotes' => count($this->votes),
            'blocks' => $this->blocks,
            'imgCount' => $this->imgCount($this->blocks),
            'shareCount' => $this->share_count,
            'createdAtHuman' => $this->created_at->diffForHumans(),
            'updatedAtHuman' => $this->updated_at->diffForHumans(),
        ];
    }
}
