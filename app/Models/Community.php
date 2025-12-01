<?php

namespace App\Models;

use App\Enums\Community\CommunityEnum;
use App\Models\Traits\HumanDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Community extends Model
{
    use HumanDates;
    protected $table = 'communities';
    protected $fillable = ['owner', 'title', 'description', 'pin', 'banner', 'rules', 'access'];
    protected $casts = CommunityEnum::class;

    /**
     * Получение списка тем
     *
     * @return BelongsToMany
     */
    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Community::class);
    }
}
