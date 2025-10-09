<?php

namespace App\Services\Share\ShareFactory;

use App\Events\Share\ShareCount;
use App\Models\Comment;
use App\Services\Share\ShareFactory\Interfaces\ShareInterface;

class CommentShare implements ShareInterface
{
    /**
     * Увеличение колличества
     * пересылок
     *
     * @param int $id
     * @param string $type
     * @return mixed
     */
    public function share(int $id): mixed
    {
        $comment = Comment::query()->find($id);

        event(new ShareCount($comment));

        return $comment;
    }
}
