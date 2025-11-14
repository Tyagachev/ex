<?php

namespace App\Models\Traits;

use App\Models\Save;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Saveable
{
    /**
     * @return MorphMany
     */
    public function saves(): MorphMany
    {
        return $this->morphMany(Save::class, 'saveable');
    }
}
