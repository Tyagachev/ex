<?php

namespace App\Services\Topic;

use App\Models\Topic;

class TopicService
{
    public function store($data)
    {
        return Topic::query()->create([
            'title' => $data['title']
        ]);
    }
}
