<?php

namespace App\Models\Traits;

use App\Models\Save;

trait Saveable
{
    public function saves()
    {
        return $this->morphMany(Save::class, 'saveable');
    }
}
