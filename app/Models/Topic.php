<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topic extends Model
{
    protected $table = 'topics';
    protected $fillable = ['title'];

    /**
     * Получения списка тем
     *
     * @return BelongsToMany
     */
    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class);
    }
}
