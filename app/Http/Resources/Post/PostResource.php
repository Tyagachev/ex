<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\UserResource;
use App\Models\Comment;
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
        $user = new UserResource($this->whenLoaded('user'));

        $commentsCount = Comment::query()
            ->where('post_id', '=', $this->id)
            ->get()
            ->count();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'viewCount' => $this->view,
            'createdAtHuman' => $this->created_at->diffForHumans(),
            'updatedAtHuman' => $this->updated_at->diffForHumans(),
            'user' => $user->resolve(),
            'commentsCount' => $commentsCount,
            'votes' => count($this->votes) ? $this->votes : [0],
            'saves' => $this->saves,
            'totalVotes' => $this->totalVotes(),
            'blocks' => $this->blocks,
            'imgCount' => $this->imgCount($this->blocks),
            'shareCount' => $this->share_count
        ];
    }
}
