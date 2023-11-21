<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Traits\UploadImage;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    use APIResponseTrait,UploadImage ;
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
            $get_images = $request->file('images');
            foreach($get_images as $image){
                $file_name  = $this->StoreImage($image,'public/Posts');
                $post->images()->create(['url'=>$file_name]);
            }
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
            $path = 'public/Posts';
            foreach($post->images as $image){
                $this->DeleteImage($path,$image);
               }
            $post->update([
                'title'      => $request->title      ?? $post->title,
                'summary'    => $request->summary    ?? $post->summary,
                'description' => $request->description ?? $post->description,
                'lang'       => $request->lang       ?? $post->lang,
            ]);
            $get_images = $request->file('images');
            foreach($get_images as $image){
                $file_name  = $this->StoreImage($image,$path);
                $post->images()->create(['url'=>$file_name]);
            }
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
            $path = 'public/Posts';
            foreach($post->images as $image){
                $this->DeleteImage($path,$image);
               }
            $post->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no post to delete');
        }
    }
}
