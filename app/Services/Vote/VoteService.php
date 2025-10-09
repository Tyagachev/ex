<?php

namespace App\Services\Vote;

use App\Services\Vote\VoteFactory\VoteFactory;

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
        $factory = VoteFactory::make($vote['type']);
        return $factory->vote($vote['id'], $vote['vote'], $vote['type']);
    }

}
