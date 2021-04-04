<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        return response()->json([
            'location' => Storage::url($request->file->store('public/editor')),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $media
     *
     * @return void
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param                          $media
     *
     * @return void
     * @throws Exception
     */
    public function update(Request $request, $media)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $media
     *
     * @return void
     */
    public function destroy($media)
    {
        //
    }
}
