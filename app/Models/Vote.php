<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Vote extends Model
{
    protected $table = 'votes';
    protected $fillable = ['user_id', 'vote', 'voteable_id', 'voteable_type'];
    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }
}
