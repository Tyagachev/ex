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
     * @param string $type
     * @return mixed
     */
    public function vote(int $id, int $vote, string $type): mixed
    {
        $comment = Comment::query()->find($id);

        $userId = Auth::id();

        $check = Vote::query()
            ->where('voteable_id', '=', $id)
            ->where('user_id', '=', $userId)
            ->where('voteable_type', 'LIKE', "%{$type}%")
            ->first();

        if (!$check) {
            $comment->votes()->create([
                'user_id' => $userId,
                'vote' => $vote,
            ]);

            return $comment;
        } elseif ($check && $check->vote != $vote) {
            $check->delete();
            return $comment;
        }
        return $comment;
    }
}
