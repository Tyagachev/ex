<?php

namespace App\Services\Save\SaveFactory\Interfaces;

interface SaveInterface
{
    /**
     * @param int $id
     * @param object|null $check
     * @param int $userId
     * @return mixed
     */
    public function saveContent(int $id, object|null $check, int $userId);
}
