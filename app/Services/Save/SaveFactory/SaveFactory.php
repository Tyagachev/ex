<?php

namespace App\Services\Save\SaveFactory;

use http\Exception\InvalidArgumentException;

class SaveFactory
{
    /**
     * Создаем класс в зависимости от
     * входящего типа
     *
     * @param string $type
     * @return CommentSave|PostSave
     */
    static function make(string $type): CommentSave|PostSave
    {
        return match ($type) {
            'post' => new PostSave(),
            'comment' => new CommentSave(),
            default => throw new InvalidArgumentException('Неизвестный тип данных')
        };
    }
}
