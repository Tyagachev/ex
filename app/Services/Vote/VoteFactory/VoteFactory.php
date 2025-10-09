<?php

namespace App\Services\Vote\VoteFactory;

use http\Exception\InvalidArgumentException;

class VoteFactory
{
    /**
     * @param string $type
     * @return CommentVote|PostVote
     */
    static function make(string $type): CommentVote|PostVote
    {
        return match ($type) {
            'post' => new PostVote(),
            'comment' => new CommentVote(),
            default => throw new InvalidArgumentException('Неизвестный тип данных')
        };
    }
}
