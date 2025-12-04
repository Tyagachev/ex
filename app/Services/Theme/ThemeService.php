<?php

namespace App\Services\Theme;

use App\Models\Theme;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;

class ThemeService
{

    /**
     * Создание темы и связи с топиком
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function store($data): JsonResponse|string
    {
        try {
            $theme = Theme::query()->create([
                'name' => $data['name']
            ]);
            $topics = Topic::query()->find($data['topicId']);
            $theme->topics()->attach($topics);
            return response()->json(['status' => 200]);
        } catch (\Exception $exception) {
                return $exception->getMessage();
        }
    }

    /**
     * Удаление темы и связки с топиками
     *
     * @param $theme
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function delete($theme): JsonResponse|string
    {
        try {
            $theme->topics()->detach($theme->topics);
            $theme->delete();
            return response()->json(['status' => 200]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
