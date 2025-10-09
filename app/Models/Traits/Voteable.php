<?php

namespace App\Models\Traits;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Voteable
{
    /**
     * @return MorphMany
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    /**
     * Сумма всех голосов
     * @return int|mixed
     */
    public function totalVotes()
    {
        return $this->votes()->sum('vote');
    }
}
