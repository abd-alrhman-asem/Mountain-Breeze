<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\APIResponseTrait;

class PostController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = Post::all();
            return $this->successResponse(PostResource::collection($posts));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no posts');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            $validated = $request->validated();
            $post = Post::create([
                'title'      => $request->title,
                'summary'    => $request->summary,
                'description' => $request->description,
                'lang'       => $request->lang,
            ]);
            return $this->successResponse(new PostResource($post));
        } catch (\Throwable $th) {
            return $this->FailResponse('create  not done');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::findORFail($id);
            return $this->successResponse(new PostResource($post));
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no post');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $post = Post::findORFail($id);
            $post->update([
                'title'      => $request->title      ?? $post->title,
                'summary'    => $request->summary    ?? $post->summary,
                'description' => $request->description ?? $post->description,
                'lang'       => $request->lang       ?? $post->lang,
            ]);
            return $this->successResponse(new PostResource($post));
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
            $post = Post::findORFail($id);
            $post->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no post to delete');
        }
    }
}
