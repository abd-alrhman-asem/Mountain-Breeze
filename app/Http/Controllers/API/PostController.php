<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;

class PostController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $posts = Post::all();
            if ($request->has('category_id')) {
                $posts = Post::where('category_id', '=', $request->category_id)->get();
            }
            return $this->successResponse(PostResource::collection($posts));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
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
                'title'       => $request->title,
                'summary'     => $request->summary,
                'description' => $request->description,
                'lang'        => $request->lang,
                'category_id' => $request->category_id,
            ]);
            return $this->successResponse(new PostResource($post));
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
            $post = Post::findORFail($id);
            return $this->successResponse(new PostResource($post));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
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
                'title'       => $request->title       ?? $post->title,
                'summary'     => $request->summary     ?? $post->summary,
                'description' => $request->description ?? $post->description,
                'lang'        => $request->lang        ?? $post->lang,
                'category_id' => $request->category_id ?? $post->category_id,
            ]);
            return $this->successResponse(new PostResource($post));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
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
            return $this->FailResponse($th);
        }
    }
}
