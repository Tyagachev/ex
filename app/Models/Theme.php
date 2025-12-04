<?php

namespace App\Models;

use App\Models\Traits\HumanDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Theme extends Model
{
    use HumanDates;

    protected $table = 'themes';
    protected $fillable = ['name'];

    /**
     * Полчучения списка топиков
     *
     * @return BelongsToMany
     */
    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class);
    }

    /**
     * Получение списка сообществ
     *
     * @return BelongsToMany
     */
    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class);
    }
}
