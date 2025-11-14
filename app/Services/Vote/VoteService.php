<?php

namespace App\Services\Vote;

use App\Models\Vote;
use App\Services\Vote\VoteFactory\VoteFactory;
use Illuminate\Support\Facades\Auth;

class VoteService
{
    /**
     * Запись голосов
     *
     * @param array $vote
     * @return mixed
     */
    public function voteStore(array $vote): mixed
    {
        $userId = Auth::id();
        $factory = VoteFactory::make($vote['type']);

        $check = Vote::query()
            ->where('voteable_id', '=', $vote['id'])
            ->where('user_id', '=', $userId)
            ->where('voteable_type', 'LIKE', "%{$vote['type']}%")
            ->first();

        return $factory->vote($vote['id'], $vote['vote'], $check, $userId);
    }

}
