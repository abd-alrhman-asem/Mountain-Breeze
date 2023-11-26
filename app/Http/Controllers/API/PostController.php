<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Traits\UploadImage;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Traits\UploadVideo;


class PostController extends Controller
{
    use APIResponseTrait, UploadImage, UploadVideo;
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
                'title'       => $request->title,
                'summary'     => $request->summary,
                'description' => $request->description,
                'lang'        => $request->lang,
                'category_id' => $request->category_id,
            ]);

            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Posts');
                $post->images()->create(['url' => $file_name , 'category_id'=>$request->category_id]);
            }

            $get_videos = $request->file('videos');
            foreach ($get_videos as $video) {
                $file_name  = $this->StoreVideo($video, 'public/Videos/Posts');
                $post->videos()->create(['video' => $file_name,'category_id'=>$request->category_id]);
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
            foreach ($post->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $path = 'public/Videos/Posts';
            foreach ($post->videos as $video) {
                $this->DeleteVideo($path, $video);
            }
            $post->update([
                'title'       => $request->title       ?? $post->title,
                'summary'     => $request->summary     ?? $post->summary,
                'description' => $request->description ?? $post->description,
                'lang'        => $request->lang        ?? $post->lang,
                'category_id' => $request->category_id ?? $post->category_id,
            ]);
            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, $path);
                $post->images()->create(['url' => $file_name]);
            }
            $get_videos = $request->file('videos');
            foreach ($get_videos as $video) {
                $file_name  = $this->StoreVideo($video, 'public/Videos/Posts');
                $post->videos()->create(['video' => $file_name]);
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
            foreach ($post->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $path = 'public/Videos/Posts';
            foreach ($post->videos as $video) {
                $this->DeleteVideo($path, $video);
            }
            $post->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no post to delete');
        }
    }
}
