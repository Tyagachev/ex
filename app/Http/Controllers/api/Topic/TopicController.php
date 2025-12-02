<?php

namespace App\Http\Controllers\api\Topic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Topic\StoreTopicRequest;
use App\Http\Resources\Topic\TopicResourse;
use App\Models\Topic;
use App\Services\Topic\TopicService;

class TopicController extends Controller
{

    /**
     * Получение списка топиков
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $topics = Topic::all()->sortByDesc('created_at');
        return TopicResourse::collection($topics);
    }

    /**
     * Сохрание названия топика
     *
     * @param StoreTopicRequest $request
     * @param TopicService $service
     * @return mixed
     */
    public function store(StoreTopicRequest $request, TopicService $service)
    {
        $data = $request->validated();
        return $service->store($data);
    }

    /**
     * Удаление топика
     *
     * @param Topic $topic
     * @return bool|null
     */
    public function destroy(Topic $topic)
    {
        return $topic->delete();
    }
}
