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
use Illuminate\Http\JsonResponse;
use Carbon\Carbon; // Import Carbon for date manipulation


class ArticleController extends Controller
{
    use APIResponseTrait, UploadImage, UploadVideo;
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['index','show','related_articles']]);
    }

    /**
     * Retrieve a paginated list of articles, applying optional filtering based on request parameters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Initiate query builder for constructing the article query
            $articlesQuery = Article::query();

            // Apply filtering based on request parameters
            if ($request->header('language')) {
                $language = Language::where('name', $request->header('language'))->first();

                if ($language) {
                    $articlesQuery->where('language_id', $language->id);
                } else {
                    return $this->errorResponse('There is no language like this.');
                }
            }

            if ($request->has('created_at')) {
                // Optimize date filtering using a range query with Carbon
                $articlesQuery->whereBetween('created_at', [
                    Carbon::parse($request->created_at)->startOfDay(),
                    Carbon::parse($request->created_at)->endOfDay(),
                ]);
            }

            if ($request->has('id')) {
                $tag = Tag::findOrFail($request->id);
                $articlesQuery->whereHas('tags', function ($query) use ($tag) {
                    $query->whereName($tag->name);
                });
            }

            // Eager load relationships to prevent N+1 query issues
            $articles = $articlesQuery->with('tags')->simplePaginate(9);

            // Check for empty results before returning
            if (!$articles->items()) {
                // Determine appropriate error message based on filters applied
                return $this->errorResponse('No articles found matching the criteria.');
            }

            // Create resource collection outside try-catch block for proper resource handling
            $articleResource = ArticleResource::collection($articles);

            return $this->successResponse($articleResource);
        } catch (\Throwable $th) {
            // Catch any exceptions and return an error response
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display a listing of the deleted resource.
     */
    public function deletedArticles(Request $request): JsonResponse
    {
        try {
            // Set a default pagination value
            $articles = Article::withTrashed()->simplePaginate();
            // Check if 'language' header is present in the request
            if ($languageHeader = $request->header('language')) {
                // Attempt to find the language by name
                $language = Language::where('name', $languageHeader)->first();

                // If language found, filter articles by language
                if ($language) {
                    $articles = Article::where('language_id', $language->id)->onlyTrashed()->simplePaginate(9);
                } else {
                    // Return error if no language found
                    return $this->errorResponse('There is no language like this.');
                }
            }
            // Check if there are no deleted articles
            if (!$articles->items()) {
                return $this->errorResponse('There are no deleted articles.');
            }
            // Return success response with paginated deleted articles
            return $this->successResponse(ArticleResource::collection($articles));
        } catch (\Throwable $th) {
            // Catch any exceptions and return an error response
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display related articles
     */
    public function related_articles(Article $article): JsonResponse
    {
        try {
            if (!$article) {
                return $this->errorResponse('there are no article for  this id ');
            }
            if (!$tags = $article->tags) {
                return $this->errorResponse("this article has no tags");
            }

            $articles = Article::whereHas('tags', function ($query) use ($article) {
                return $query->whereIn('name', $article->tags->pluck('name'));
            })->where('language_id', '=', $article->language_id)
                ->where('id', '!=', $article->id)->get();
            if (!$articles) {
                return $this->errorResponse("there are no articles related ");
            }
            return $this->successResponse( ArticleResource::collection($articles) ,200 );
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a new article in the database.
     *
     * @param StoreArticleRequest $request
     * @return JsonResponse
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        try {
            // Validate the request data using the StoreArticleRequest class
            $validated = $request->validated();

            // Create a new Article instance
            $article = new Article();

            // Populate the article attributes from the validated request data
            $article->title = $request->title;
            $article->summary = $request->summary;
            $article->description = $request->description;
            $article->language_id = $request->language_id;

            // Save the article to the database
            $article->save();

            // Attach the specified tags to the article
            $article->tags()->attach($request->tags);

            // Return a success response with the created article resource
            return $this->createdResponse('article ');
        } catch (\Throwable $th) {
            // Catch any exceptions and return an error response
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Retrieve a single article based on ID, optionally filtering by language header.
     *
     * @param Article $article
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Article $article, Request $request): JsonResponse
    {
        try {
            // Apply language filtering if header is present
            if ($request->header('language')) {
                $language = Language::where('name', $request->header('language'))->first();

                if ($language) {
                    // Ensure language matches the article's language
                    if ($language->id === $article->language_id) {
                        // Retrieve the article with the specified language and ID
                        $article = Article::where('language_id', $language->id)
                            ->where('id', $article->id)->first();
                    } else {
                        return $this->errorResponse('lang docent match');
                    }
                } else {
                    return $this->errorResponse('There is no language like this.');
                }
            }

            // Return the article as a resource
            return $this->successResponse(new ArticleResource($article));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Updates an existing article in the database.
     *
     * @param UpdateArticleRequest $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        try {

            // this need to back to it photo not working
            $path = 'public/Articles';
            foreach ($article->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $path = 'public/Videos/Articles';
            foreach ($article->videos as $video) {
                $this->DeleteVideo($path, $video);
            }


            $article->update([
                'title' => $request->title             ?? $article->title,
                'summary' => $request->summary         ?? $article->summary,
                'description' => $request->description ?? $article->description,
                'language_id' => $request->language_id ?? $article->language_id,
            ]);
            $article->tags()->sync($request->tags);

            return $this->successOperationResponse("articles updated successfully");
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Deletes an existing article from the database (or marks it as deleted if using soft deletes).
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        try {
            // Delete the article (or mark it as deleted if using soft deletes)
            $article->delete();

            // Return a success response with an appropriate message
            return $this->successOperationResponse("Article moved to trash"); // Adjust message if not using soft deletes
        } catch (\Throwable $th) {
            // Catch any exceptions and return an error response
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    public function forceDestroy(string $id): JsonResponse
    {
        try {

            $article = Article::onlyTrashed()->find($id);

            if(!$article)
              return $this->errorResponse('article not found in trash ');

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
             return $this->successOperationResponse("article deleted forever successfully ");
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}

