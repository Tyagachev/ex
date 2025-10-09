<?php

namespace App\Services\Share\ShareFactory;

use http\Exception\InvalidArgumentException;

class ShareFactory
{
    /**
     * @param string $type
     * @return CommentShare|PostShare
     */
    static function make(string $type): CommentShare|PostShare
    {
        return match ($type) {
            'post' => new PostShare(),
            'comment' => new CommentShare(),
            default => throw new InvalidArgumentException('Неизвестный тип данных')
        };
    }
}
