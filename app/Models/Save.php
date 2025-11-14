<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Save extends Model
{
    protected $table = 'saves';
    protected $fillable = ['user_id', 'saveable_id', 'saveable_type'];

    /**
     * @return MorphTo
     */
    public function saveable(): MorphTo
    {
        return $this->morphTo();
    }
}
