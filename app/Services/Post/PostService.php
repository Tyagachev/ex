<?php

namespace App\Services\Post;

use App\Actions\UploadImage\UploadImageFactory;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

class PostService
{
    /**
     * Сохранение блоков поста
     *
     * @param array $data
     * @param object $request
     * @return mixed
     */
    public function storePost(array $data, object $request): mixed
    {
        $authUserId = Auth::id();

        $blocks = [];

        foreach ($data['blocks'] as $i => $block) {
            if ($block['type'] === 'image' && $request->hasFile("blocks.$i.file")) {
                $imageFactory = UploadImageFactory::make($authUserId, $data['from']);
                $blocks[] = $imageFactory->upload($block['file'], $block['type']);
            } else {
                $blocks[] = [
                    'type' => $block['type'],
                    'content' => Purifier::clean($block['content'] ?? '')
                ];
            }
        }

        return Post::query()->create([
            'user_id' => $authUserId,
            'title' => strip_tags(trim($data['title'])),
            'blocks' => $blocks,
        ]);
    }

    /**
     * Обновление поста
     *
     * @param array $data
     * @param object $request
     * @param object $post
     * @return object
     * @throws \Exception
     */
    public function updatePost(array $data, object $request, object $post): object
    {
        $authUserId = Auth::id();
        $blocks = [];

        foreach ($data['blocks'] as $i => $block) {
            if ($block['type'] === 'image') {
                if ($request->hasFile("blocks.$i.file")) {
                    // новая картинка → загружаем на S3
                    $imageFactory = UploadImageFactory::make($authUserId, $data['from']);

                    $blocks[] = $imageFactory->upload($block['file'], $block['type']);
                } elseif (!empty($block['path'])) {
                    // старая картинка → оставляем ссылку
                    $blocks[] = [
                        'type' => 'image',
                        'path' => $block['path'],
                    ];
                }
            } else {
                // текстовый блок
                $blocks[] = [
                    'type' => $block['type'],
                    'content' => Purifier::clean($block['content'] ?? '')
                ];
            }
        }

        $diff = self::checkCollectionDiff($request, $post);

        if (!$diff->isEmpty()) {
            self::deleteImageFromS3($diff);
        }

        $post->update([
            'title' => strip_tags($data['title']),
            'blocks' => $blocks,
        ]);

        return $post;
    }

    /**
     * Сарвнивает какие картинки есть
     * у поста и каких нет в запросе
     *
     * @param object $post
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    private function checkCollectionDiff(object $request, object $post): Collection
    {
        $postCollection  = collect($post['blocks'])->where('type', 'image');
        $requestCollection = collect($request['blocks'])->where('type', 'image');

        $diff = $postCollection->filter(function ($item) use ($requestCollection) {
            return !$requestCollection->contains('path', $item['path']);
        });

        return $diff;
    }

    /**
     * Удаляет картинки из S3
     * которые убрали при редактировании
     * поста
     *
     * @param $diff
     * @return void
     */
    private function deleteImageFromS3($diff): void
    {
        foreach ($diff as $item) {
            if ($item['type'] === 'image') {
                $url = parse_url($item['path']);
                $e = explode('/', $url['path']);
                $file = array_pop($e);

                Storage::disk('s3')->delete('users/' . Auth::id() . '/posts/' . $file);
            }
        }
    }
}
