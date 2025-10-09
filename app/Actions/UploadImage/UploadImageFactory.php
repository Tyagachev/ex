<?php

namespace App\Actions\UploadImage;

use http\Exception\InvalidArgumentException;

class UploadImageFactory
{
    /**
     * Выбор класса подгрузки изображения
     *
     * @param int $authUserId
     * @param string $from
     * @return UploadPostImage
     */
    static function make(int $authUserId, string $from): UploadPostImage
    {
        return match ($from) {
            'post' => new UploadPostImage($authUserId),
            default => throw new InvalidArgumentException('Неизвестный тип данных')
        };
    }
}
