<?php

namespace App\Models;

use App\Models\Traits\HumanDates;
use App\Models\Traits\Saveable;
use App\Models\Traits\Voteable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
     * Создатель поста
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Коммнетарии к посту
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('parent_id', null)->orderBy('created_at', 'desc');
    }

    /**
     * Изображение поста
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class, 'post_id');
    }

    /**
     * Простмотры поста
     *
     * @return HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class, 'post_id');
    }

    /**
     * @return int
     */
    public function totalViews(): int
    {
        return $this->hasMany(View::class)->count();
    }

    /**
     * Добавление просмотров
     *
     * @param $userId
     * @param $ipAddress
     * @param $userAgent
     * @return mixed
     */
    public function addView($userId, $ipAddress, $userAgent): mixed
    {
        if (!is_null($userId)) {
            $existingView = $this->views()
                ->where('user_id', $userId)
                ->where('post_id', $this->id)
                ->first();

            if ($existingView) {
                $existingView->update([
                    'duration' => $existingView->duration + 1,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'updated_at' => Carbon::now()
                ]);
                Log::info($existingView);
                return $existingView;
            }
        }
        return $this->views()->create([
            'user_id' => $userId,
            'duration' => 0, // среднее время прочтения
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }
}
