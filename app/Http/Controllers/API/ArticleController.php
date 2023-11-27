<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Language;
use App\Models\Tag;
use App\Traits\APIResponseTrait;
use Illuminate\Http\Request;
use App\Traits\UploadImage;
use App\Traits\UploadVideo;

class ArticleController extends Controller
{
    use APIResponseTrait, UploadImage, UploadVideo;
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Get articles details",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function index(Request $request)
    {
        try {
            $articles = Article::paginate(9);

            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $articles = Article::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->paginate(9);
            }

            if ($request->has('created_at')) {
                $articles = Article::where('created_at', '=', $request->created_at)->paginate(9);
            }

            if ($request->has('id')) {
                $tagName = Tag::findOrFail($request->id);
                $articles = Article::whereHas('tags', function ($query) use ($tagName) {
                    $query->whereName($tagName->name);
                })->paginate(9);
            }
            return $this->successResponse(ArticleResource::collection($articles));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display a listing of the deleted resource.
     */
    public function deleted_articles(Request $request)
    {
        try {
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $articles = Article::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->onlyTrashed()->get();
            }
            return $this->successResponse(ArticleResource::collection($articles));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display related articles
     */
    public function related_articles(Request $request)
    {
        try {
            $article = Article::find($request->id);
            if (!isset($article)) {
                return $this->FailResponse('there is no article for  this id ');
            }
            $tags = $article->tags;
            if ($tags->isEmpty()) {
                return $this->FailResponse("this article has no tags");
            }

            $articles = Article::whereHas('tags', function ($query) use ($article) {
                return $query->whereIn('name', $article->tags->pluck('name'));
            })->where('language_id', '=', $article->language_id)
                ->where('id', '!=', $article->id)->get();


            if ($articles->isEmpty()) {
                return $this->FailResponse("there is no article related ");
            }
            return $this->successResponse(ArticleResource::collection($articles));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/articles",
     *     summary="Create a new article",
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Article title",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *        @OA\Parameter(
     *         name="summary",
     *         in="query",
     *         description="Article summary",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *        @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Article description",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *        @OA\Parameter(
     *         name="lang",
     *         in="query",
     *         description="Article lang",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Article created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function store(StoreArticleRequest $request)
    {

        try {
            $validated = $request->validated();

            $article = new Article();

            $article->title = $request->title;
            $article->summary = $request->summary;
            $article->description = $request->description;
            $article->language_id = $request->language_id;

            $article->save();

            $article->tags()->attach($request->tags);

            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Articles');
                $article->images()->create(['url' => $file_name]);
            }

            $get_videos = $request->file('videos');
            foreach ($get_videos as $video) {
                $file_name  = $this->StoreVideo($video, 'public/Videos/Articles');
                $article->videos()->create(['video' => $file_name]);
            }
            return $this->successResponse(new ArticleResource($article));
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
            $article = Article::findOrFail($id);

            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                //return [$name->id , $article->language_id];
                if ($language->id == $article->language_id) {
                    $article = Article::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id);
                    })->where('id', '=', $id)->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }
            return $this->successResponse(new ArticleResource($article));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $article = Article::findOrFail($id);
            $path = 'public/Articles';
            foreach ($article->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $path = 'public/Videos/Articles';
            foreach ($article->videos as $video) {
                $this->DeleteVideo($path, $video);
            }
            $article->update([
                'title' => $request->title ?? $article->title,
                'summary' => $request->summary ?? $article->summary,
                'description' => $request->description ?? $article->description,
                'language_id' => $request->language_id ?? $article->language_id,
            ]);

            $article->tags()->sync($request->tags);

            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Articles');
                $article->images()->create(['url' => $file_name]);
            }
            $get_videos = $request->file('videos');
            foreach ($get_videos as $video) {
                $file_name  = $this->StoreVideo($video, 'public/Videos/Articles');
                $article->videos()->create(['video' => $file_name]);
            }
            return $this->successResponse(new ArticleResource($article));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            $article->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
    public function forceDestroy(string $id)
    {
        try {
            $article = Article::onlyTrashed()->find($id);
            $path = 'public/Articles';
            foreach ($article->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $path = 'public/Videos/Articles';
            foreach ($article->videos as $video) {
                $this->DeleteVideo($path, $video);
            }
            $article->tags()->detach();
            Article::where('id', '=', $id)->forceDelete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
