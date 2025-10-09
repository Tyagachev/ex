<?php

namespace App\Services\Share\ShareFactory;

use App\Events\Share\ShareCount;
use App\Models\Post;
use App\Services\Share\ShareFactory\Interfaces\ShareInterface;

class PostShare implements ShareInterface
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
        $post = Post::query()->find($id);
        event(new ShareCount($post));

        return $post;
    }
}
