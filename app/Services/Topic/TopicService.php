<?php

namespace App\Services\Topic;

use App\Models\Theme;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;

class TopicService
{
    /**
     * Сохранение топика
     *
     * @param $data
     * @return JsonResponse|string
     */
    public function store($data)
    {
        try {
            Topic::query()->create([
                'title' => $data['title']
            ]);
            return response()->json(['status' => 200]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Удаление топика и его связей
     *
     * @param $topic
     * @return JsonResponse|string
     */
    public function delete($topic): JsonResponse|string
    {
        try {
            $topic->themes()->detach($topic->themes);
            $topic->delete();
            return response()->json(['status' => 200]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
