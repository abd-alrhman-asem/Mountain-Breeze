<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Traits\APIResponseTrait;

class TagController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tag = Tag::all();
            return $this->successResponse(TagResource::collection($tag));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no tag');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest  $request)
    {
        try {
            $validated = $request->validated();

           $tag = Tag::create([
            'name'=>$request->name,
            'lang'=>$request->lang,
           ]);

            return $this->successResponse(new TagResource($tag));

        } catch (\Throwable $th) {
            return $this->FailResponse('create not done');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $tag = Tag::findOrFail($id);
            return $this->successResponse(new TagResource($tag));
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no tag');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $tag = Tag::findOrFail($id);

            $tag->update([
                'name' => $request->name ?? $tag->name,
                'lang' => $request->lang ?? $tag->lang,
            ]);
            return $this->successResponse(new TagResource($tag));

        } catch (\Throwable $th) {
            return $this->FailResponse('update not done');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();
            return $this->successResponse(new TagResource($tag));

        } catch (\Throwable $th) {
            return $this->FailResponse('there is no tag to delete');
        }
    }
}
