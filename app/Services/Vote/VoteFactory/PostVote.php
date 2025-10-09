<?php

namespace App\Services\Vote\VoteFactory;

use App\Models\Post;
use App\Models\Vote;
use App\Services\Vote\VoteFactory\Interfaces\VoteInterface;
use Illuminate\Support\Facades\Auth;

class PostVote implements VoteInterface
{
    /**
     * Проверка
     * запись или удаление
     *
     * @param int $id
     * @param int $vote
     * @param string $type
     * @return mixed
     */
    public function vote(int $id, int $vote, string $type): mixed
    {
        $post = Post::query()->find($id);

        $userId = Auth::id();

        $check = Vote::query()
            ->where('voteable_id', '=', $id)
            ->where('user_id', '=', $userId)
            ->where('voteable_type', 'LIKE', "%{$type}%")
            ->first();

        if (!$check) {

            $post->votes()->create([
                'user_id' => $userId,
                'vote' => $vote,
            ]);

            return $post;
        } elseif ($check && $check->vote != $vote) {
            $check->delete();
            return $post;
        }
        return $post;
    }
}
