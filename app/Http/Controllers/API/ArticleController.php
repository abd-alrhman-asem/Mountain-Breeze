<?php

namespace App\Http\Controllers\API;

use DateTime;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Article;
use App\Models\Article_Tags;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;



class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $articles = Article::all();

        if($request->has('created_at')){
            $articles= Article::where('created_at','=', $request->created_at)->get();
        }

        if($request->has('id'))
        {
            $tagName = Tag::findOrFail($request->id);
            $articles = Article::whereHas('tags', function ($query) use ($tagName) {
                $query->whereName($tagName->name);
            })->get();
        }

        return response()->json(ArticleResource::collection($articles), 200);
    }

    /**
     * Display a listing of the deleted resource.
     */
    public function deleted_articles()
    {
        $articles = Article::onlyTrashed()->get();
        return response()->json(ArticleResource::collection($articles), 200);
    }

    /**
     * Display related articles
     */
    public function related_articles(string $id)
    {
        $article = Article::findOrFail($id);

        $tags = $article->tags;

        foreach ($tags as $tag) {
            $related_articles = $tag->articles->all();
        }

        return response()->json(ArticleResource::collection($related_articles), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest  $request)
    {

        $validated = $request->validated();

        $article = new Article();

        $article->title = $request->title;
        $article->summary = $request->summary;
        $article->description = $request->description;
        $article->lang = $request->lang;

        $article->save();

        $article->tags()->attach($request->tags);

        return response()->json(new ArticleResource($article), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::findOrFail($id);

        return response()->json(new ArticleResource($article), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validated = $request->validated();

        $article->update([
            'title' => $request->title ?? $article->title,
            'summary' => $request->summary ?? $article->summary,
            'description' => $request->description ?? $article->description,
            'lang' => $request->lang ?? $article->lang,
        ]);

        $article->tags()->sync($request->tags);

        return response()->json(new ArticleResource($article), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->tags()->detach();
        return response()->json('Deleted Done', 200);
    }
}
