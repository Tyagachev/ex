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
     * @param object|null $check
     * @return mixed
     */
    public function vote(int $id, int $vote, object|null $check, int $userId): mixed
    {
        $post = Post::query()->find($id);

        if (!$check) {
            $post->votes()->create([
                'user_id' => $userId,
                'vote' => $vote,
            ]);
            return $post;

        } elseif (gettype($check) === 'object'&& $check->vote != $vote) {
            $check->delete();
            return $post;
        }
        return $post;
    }
}
