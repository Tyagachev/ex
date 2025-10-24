<?php

namespace App\Actions\UploadImage;

use App\Actions\UploadImage\Compression\CompressionImage;
use App\Actions\UploadImage\Interfaces\UploadInterface;
use Illuminate\Support\Facades\Storage;


class UploadPostImage extends CompressionImage implements UploadInterface
{
    public string $authId;
    public function __construct($authId)
    {
        $this->authId = $authId;
    }

    /**
     * Загрузка изображения в S3
     *
     * @param object $file
     * @param string $type
     * @return array
     */
    public function upload(object $file, string $type): array
    {
        try {
            $encoded = $this->compressor($file);

            $path = 'users/'. $this->authId .'/posts';

            Storage::disk('s3')->put($path . '/' . $encoded['fileName'], $encoded['encode']);

            $url = Storage::disk('s3')->url($path . '/' . $encoded['fileName']);

            return [
                'type' => $type,
                'path' => $url
            ];
        } catch (\Exception $e) {
            \Log::error('Ошибка загрузки изображения: ' . $e->getMessage());
            throw new \Exception('Ошибка загрузки изображения: ' . $e->getMessage());
        }
    }
}
