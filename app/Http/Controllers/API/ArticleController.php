<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Tag;
use App\Traits\APIResponseTrait;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $articles = Article::all();

            if ($request->has('created_at')) {
                $articles = Article::where('created_at', '=', $request->created_at)->get();
            }

            if ($request->has('id')) {
                $tagName = Tag::findOrFail($request->id);
                $articles = Article::whereHas('tags', function ($query) use ($tagName) {
                    $query->whereName($tagName->name);
                })->get();
            }
            return $this->successResponse(ArticleResource::collection($articles));
        } catch (\Throwable $th) {
            return $this->FailResponse('There are no articles');
        }
    }

    /**
     * Display a listing of the deleted resource.
     */
    public function deleted_articles()
    {
        try {
            $articles = Article::onlyTrashed()->get();
            return $this->successResponse(ArticleResource::collection($articles));
        } catch (\Throwable $th) {
            return $this->FailResponse('There are no articles');
        }
    }

    /**
     * Display related articles
     */
    public function related_articles(string $id)
    {
        try {
            $article = Article::findOrFail($id);

            $tags = $article->tags;

            foreach ($tags as $tag) {
                $related_articles = $tag->articles->all();
            }
            return $this->successResponse(ArticleResource::collection($related_articles));
        } catch (\Throwable $th) {
            return $this->FailResponse('There are no articles');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {

        try {
            $validated = $request->validated();

            $article = new Article();

            $article->title = $request->title;
            $article->summary = $request->summary;
            $article->description = $request->description;
            $article->lang = $request->lang;

            $article->save();

            $article->tags()->attach($request->tags);
            return $this->successResponse(new ArticleResource($article));
        } catch (\Throwable $th) {
            return $this->FailResponse(' Create not done');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $article = Article::findOrFail($id);
            return $this->successResponse(new ArticleResource($article));
        } catch (\Throwable $th) {
            return $this->FailResponse('There is no article');
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

            $article->update([
                'title' => $request->title ?? $article->title,
                'summary' => $request->summary ?? $article->summary,
                'description' => $request->description ?? $article->description,
                'lang' => $request->lang ?? $article->lang,
            ]);

            $article->tags()->sync($request->tags);
            return $this->successResponse(new ArticleResource($article));
        } catch (\Throwable $th) {
            return $this->FailResponse(' update not done');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            $article->tags()->detach();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse(' delete not done');
        }
    }
}
