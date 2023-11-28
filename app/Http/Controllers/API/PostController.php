<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Models\Language;
use App\Traits\UploadImage;
use App\Traits\UploadVideo;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;


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
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $posts = Post::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->get();
            }
            $args['data'] =PostResource::collection($posts);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
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
                'language_id' => $request->language_id,
                'category_id' => $request->category_id,
            ]);

            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Posts');
                $post->images()->create(['url' => $file_name, 'category_id' => $request->category_id]);
            }

            $get_videos = $request->file('videos');
            foreach ($get_videos as $video) {
                $file_name  = $this->StoreVideo($video, 'public/Videos/Posts');
                $post->videos()->create(['video' => $file_name, 'category_id' => $request->category_id]);
            }
            $args['message'] = 'post stored successfully ';
            $args['data'] =new PostResource($post);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        try {
            $post = Post::findORFail($id);
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                if ($language->id == $post->language_id) {
                    $post = Post::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id);
                    })->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }
            $args['data'] = new PostResource($post);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
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
                'language_id' => $request->language_id           ?? $post->language_id,
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
            $args['message'] = 'post updated successfully ';
            $args['data'] =new PostResource($post);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
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
            $args['message'] = 'post deleted successfully ';
            return $this->successResponse($args , 200);
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
