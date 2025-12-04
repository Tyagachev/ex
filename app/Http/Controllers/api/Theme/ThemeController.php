<?php

namespace App\Http\Controllers\api\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\Theme\StoreThemeRequest;
use App\Http\Resources\Theme\ThemeResource;
use App\Models\Theme;
use App\Services\Theme\ThemeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ThemeController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $themes = Theme::all()->sortByDesc('created_at');
        return ThemeResource::collection($themes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Сохранение темы и топика
     *
     * @param StoreThemeRequest $request
     * @param ThemeService $service
     * @return JsonResponse|string
     */
    public function store(StoreThemeRequest $request, ThemeService $service): JsonResponse|string
    {
        $data = $request->validated();
        return $service->store($data);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ThemeService $service)
    {
        //
    }


    /**
     * Удаления темы и топика
     *
     * @param Theme $theme
     * @param ThemeService $service
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function destroy(Theme $theme, ThemeService $service): JsonResponse|string
    {
        return $service->delete($theme);
    }
}
