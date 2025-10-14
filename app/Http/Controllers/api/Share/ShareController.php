<?php

namespace App\Http\Controllers\api\Share;

use App\Http\Controllers\Controller;
use App\Services\Share\ShareService;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Увеличение числа пересылок
     *
     * @param Request $request
     * @param ShareService $service
     * @return mixed
     */
    public function store(Request $request, ShareService $service): mixed
    {
        return $service->storeShare($request->all());
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
