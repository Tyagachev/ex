<?php

namespace App\Models;

use App\Models\Traits\HumanDates;
use App\Models\Traits\Voteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use HumanDates;
    use Voteable;

    protected $table = 'posts';

    protected $fillable = ['title', 'user_id', 'blocks', 'share_count'];
    protected $casts = [
      'blocks' => 'array'
    ];

    protected $dates = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->where('parent_id', null);
    }

    public function images()
    {
        return $this->hasMany(PostImage::class, 'post_id');
    }

}
