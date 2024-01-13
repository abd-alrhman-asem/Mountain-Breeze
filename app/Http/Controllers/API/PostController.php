<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Models\Language;
use App\Traits\UploadImage;
use App\Traits\UploadVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;


class PostController extends Controller
{
    use APIResponseTrait, UploadImage, UploadVideo;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $posts = Post::query();
            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                if (!$language)
                    return $this->errorResponse('please enter a valid language');
                $posts->where('language_id', '=', $language->id);
            }
            return $this->successResponse(PostResource::collection($posts->get()));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        try {
            $post = Post::create([
                'title' => $request->title,
                'summary' => $request->summary,
                'description' => $request->description,
                'language_id' => $request->language_id,
                'category_id' => $request->category_id,
            ]);

            if ($get_images = $request->file('images')) {
                foreach ($get_images as $image) {
                    $file_name = $this->StoreImage($image, 'public/Posts');
                    $post->images()->create(['url' => $file_name, 'category_id' => $request->category_id]);
                }
            }
            if ($get_videos = $request->file('videos')) {
                foreach ($get_videos as $video) {
                    $file_name = $this->StoreVideo($video, 'public/Videos/Posts');
                    $post->videos()->create(['video' => $file_name, 'category_id' => $request->category_id]);
                }
            }
            return $this->successOperationResponse('post created successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return $this->errorResponse('there are no post for this id ');
            }
            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();

                if ($language->id != $post->language_id) {
                    return $this->errorResponse('this post for another language ');
                }
            }
            return $this->successResponse(new PostResource($post));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id): JsonResponse
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return $this->errorResponse('there are no post for this id ');
            }
            if ($request->hasFile('images')) {
                $path = 'public/Posts';
                foreach ($post->images as $image) {
                    $this->DeleteImage($path, $image);
                }
                $get_images = $request->file('images');
                foreach ($get_images as $image) {
                    $file_name = $this->StoreImage($image, $path);
                    $post->images()->create(['url' => $file_name]);
                }
            }
            if ($request->hasFile('videos')) {
                $path = 'public/Videos/Posts';
                foreach ($post->videos as $video) {
                    $this->DeleteVideo($path, $video);
                }
                $get_videos = $request->file('videos');
                foreach ($get_videos as $video) {
                    $file_name = $this->StoreVideo($video, $path);
                    $post->videos()->create(['video' => $file_name]);
                }
            }
            $post->update([
                'title' => $request->title ?? $post->title,
                'summary' => $request->summary ?? $post->summary,
                'description' => $request->description ?? $post->description,
                'language_id' => $request->language_id ?? $post->language_id,
                'category_id' => $request->category_id ?? $post->category_id,
            ]);
            return $this->successOperationResponse('post updated successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return $this->errorResponse('there are no post for this id ');
            }
            foreach ($post->images as $image) {
                $this->DeleteImage('public/Posts', $image);
            }
            foreach ($post->videos as $video) {
                $this->DeleteVideo('public/Videos/Posts', $video);
            }
            $post->delete();
            return $this->successOperationResponse( 'post deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
