<?php

namespace App\Services\Vote\VoteFactory\Interfaces;

interface VoteInterface
{
    public function vote(int $id, int $vote, object|null $check, int $userId);
}
