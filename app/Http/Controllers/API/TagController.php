<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tag = Tag::all();
        return response()->json(TagResource::collection($tag), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest  $request)
    {
        $validated = $request->validated();

        $tag = new Tag();

        $tag->name = $request->name;

        $tag->save();

        return response()->json(new TagResource($tag), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json(new TagResource($tag), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $validated = $request->validated();

        $tag->update([
            'name' => $request->name??$tag->name,
        ]);

        return response()->json(new TagResource($tag), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json('Deleted Done', 200);
    }
}
