<?php

namespace App\Actions\UploadImage\Interfaces;

interface UploadInterface
{
    public function upload(object $file, string $type);
}
