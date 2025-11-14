<?php

namespace App\Services\Save\SaveFactory;

use App\Models\Comment;
use App\Services\Save\SaveFactory\Interfaces\SaveInterface;

class CommentSave implements SaveInterface
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
        $comment = Comment::query()->find($id);

        if (!$check) {
            $comment->saves()->create([
                'user_id' => $userId,
            ]);
            return $comment->saves;

        } elseif (gettype($check) === 'object') {
            $check->delete();
            return $comment->saves;
        }
        return $comment->saves;
    }
}
