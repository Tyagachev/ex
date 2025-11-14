<?php

namespace App\Services\Save;


use App\Models\Save;
use App\Services\Save\SaveFactory\SaveFactory;
use Illuminate\Support\Facades\Auth;

class SaveService
{
    public function store($data)
    {
        $userId = Auth::id();
        $factory = SaveFactory::make($data['type']);

        $check = Save::query()
            ->where('saveable_id', '=', $data['id'])
            ->where('user_id', '=', $userId)
            ->where('saveable_type', 'LIKE', "%{$data['type']}%")
            ->first();

        return $factory->saveContent($data['id'], $check, $userId);
    }
}
