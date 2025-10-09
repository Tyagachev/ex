<?php

namespace App\Services\Comment;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    /**
     * @param object $post
     * @param object $request
     * @return string
     */
    public function storeComment(object $post, object $request)
    {
        try {
            return Comment::query()->create([
                'text' => $request['text'],
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'parent_id' => $request['parentId'],
                'reply_user_id' => $request['replyId']
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param object $comment
     * @return \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    public function editComment(object $comment)
    {
        $comment = Comment::query()->find($comment->id);

        return $comment->text;
    }

    /**
     * @param object $comment
     * @param object $request
     * @return mixed
     */
    public function updateComment(object $comment, object $request)
    {
         return $comment->update([
             'text' => $request->input('text')
        ]);
    }
}
