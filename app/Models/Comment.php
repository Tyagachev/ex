<?php

namespace App\Models;

use App\Models\Traits\HumanDates;
use App\Models\Traits\Saveable;
use App\Models\Traits\Voteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;
    use HumanDates;
    use Voteable;
    use Saveable;

    protected $table = 'comments';
    protected $guarded = [];

    /**
     * Возвращает юзера коммента
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Возвращает ответы на комментарии
     *
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with(['user', 'replyUser', 'replies'])->orderBy('created_at', 'desc');
    }

    /**
     * Возвращает юзера
     * ответившего на комментарий
     *
     * @return BelongsTo
     */
    public function replyUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reply_user_id');
    }

    /**
     * Возвращает пост которому
     * принадлежит коммент
     *
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

}
