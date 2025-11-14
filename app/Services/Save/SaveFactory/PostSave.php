<?php

namespace App\Services\Save\SaveFactory;

use App\Models\Post;
use App\Services\Save\SaveFactory\Interfaces\SaveInterface;

class PostSave implements SaveInterface
{
    /**
     * Проверка
     * запись или удаление
     *
     * @param int $id
     * @param object|null $check
     * @param int $userId
     * @return mixed
     */
    public function saveContent(int $id, object|null $check, int $userId): mixed
    {
        $post = Post::query()->find($id);

        if (!$check) {
            $post->saves()->create([
                'user_id' => $userId,
            ]);
            return $post->saves;

        } elseif (gettype($check) === 'object') {
            $check->delete();
            return $post->saves;
        }
        return $post->saves;
    }
}
