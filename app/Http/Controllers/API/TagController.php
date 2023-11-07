<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use Illuminate\Http\Request;
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
            return $this->FailResponse($th);
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
            return $this->FailResponse($th);
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
            return $this->FailResponse($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try {
            $validated = $request->validated();

            $tag->update([
                'name' => $request->name ?? $tag->name,
                'lang' => $request->lang ?? $tag->lang,
            ]);
            return $this->successResponse(new TagResource($tag));

        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();
            return $this->successResponse(new TagResource($tag));

        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }
}
