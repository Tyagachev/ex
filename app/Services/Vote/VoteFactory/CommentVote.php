<?php

namespace App\Services\Vote\VoteFactory;

use App\Models\Comment;
use App\Models\Vote;
use App\Services\Vote\VoteFactory\Interfaces\VoteInterface;
use Illuminate\Support\Facades\Auth;

class CommentVote implements VoteInterface
{

    /**
     * Проверка
     * запись или удаление
     *
     * @param int $id
     * @param int $vote
     * @param object|null $check
     * @param int $userId
     * @return mixed
     */
    public function vote(int $id, int $vote, object|null $check, int $userId): mixed
    {
        $comment = Comment::query()->find($id);

        if (!$check) {
            $comment->votes()->create([
                'user_id' => $userId,
                'vote' => $vote,
            ]);

            return $comment;
        } elseif (gettype($check) === 'object'&& $check->vote != $vote) {
            $check->delete();
            return $comment;
        }
        return $comment;
    }
}
