<?php

namespace App\Actions\UploadImage\Compression;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class CompressionImage
{
    /**
     * Уменьшает размер файла
     * возвращает имя файла
     * и сжатое изображение
     *
     * @param $file
     * @return array
     * @throws \Exception
     */
    protected function compressor(object $file): array
    {
        ini_set('memory_limit', '300M');

        try {
            $manager = new ImageManager(new Driver());

            $image = $manager->read($file->getRealPath());

            $origExt = strtolower($file->getClientOriginalExtension());

            switch ($origExt) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                    $encoder = new JpegEncoder(quality: 60);
                    $targetFormat = 'jpg';
                    break;
                case 'webp':
                    $encoder = new WebpEncoder(quality: 80);
                    $targetFormat = 'webp';
                    break;
                case 'gif':
                    $encoder = new GifEncoder(interlaced: 8);
                    $targetFormat = 'gif';
                    break;
                default:
                    $encoder = new JpegEncoder(quality: 60);
                    $targetFormat = 'jpg';
            }

            $imageContent = $image->encode($encoder)->toString();

            $fileName = uniqid() . '.' . $targetFormat;

            return [
                'fileName' => $fileName,
                'encode' => $imageContent
            ];

        }catch (\Exception $e) {
            \Log::error('Ошибка сжатия изображения: ' . $e->getMessage());
            throw new \Exception('Ошибка сжатия изображения: ' . $e->getMessage());
        } finally {
            if (isset($image)) {
                unset($image);
            }
            if (isset($encoded)) {
                unset($encoded);
            }
            if (isset($manager)) {
                unset($manager);
            }
            gc_collect_cycles();
        }
    }
}
