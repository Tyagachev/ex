<?php

namespace App\Models;

use App\Models\Traits\HumanDates;
use App\Models\Traits\Saveable;
use App\Models\Traits\Voteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;
    use HumanDates;
    use Voteable;
    use Saveable;

    protected $table = 'posts';

    protected $fillable = ['title', 'user_id', 'blocks', 'share_count'];
    protected $casts = [
      'blocks' => 'array'
    ];

    protected $dates = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('parent_id', null)->orderBy('created_at', 'desc');
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class, 'post_id');
    }
}
