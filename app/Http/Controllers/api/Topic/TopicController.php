<?php

namespace App\Http\Controllers\api\Topic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Topic\StoreTopicRequest;
use App\Http\Resources\Topic\TopicResourse;
use App\Models\Topic;
use App\Services\Topic\TopicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TopicController extends Controller
{

    /**
     * Получение списка топиков
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $topics = Topic::all()->sortByDesc('created_at');
        return TopicResourse::collection($topics);
    }


    /**
     * Сохрание названия топика
     *
     * @param StoreTopicRequest $request
     * @param TopicService $service
     * @return JsonResponse|string
     */
    public function store(StoreTopicRequest $request, TopicService $service): JsonResponse|string
    {
        $data = $request->validated();
        return $service->store($data);
    }

    /**
     * Удаление топика
     *
     * @param Topic $topic
     * @param TopicService $service
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function destroy(Topic $topic, TopicService $service): JsonResponse|string
    {
        return $service->delete($topic);
    }
}
