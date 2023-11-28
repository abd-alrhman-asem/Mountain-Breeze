<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use App\Models\Language;
use App\Traits\APIResponseTrait;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/tags",
     *     summary="Get tags details",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function index(Request $request)
    {
        try {
            $tag = Tag::all();
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $tag = Tag::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->get();
            }
            return $this->successResponse(TagResource::collection($tag));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/tags",
     *     summary="Create a new tag",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Article name",
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
     *     @OA\Response(response="200", description="tag created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function store(StoreTagRequest  $request)
    {
        try {
            $validated = $request->validated();

            $tag = Tag::create([
                'name' => $request->name,
                'language_id' => $request->language_id,
            ]);

            return $this->successResponse(new TagResource($tag),'store');
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag, Request $request)
    {
        try {
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                if ($language->id == $tag->language_id) {
                    $tag = Tag::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id);
                    })->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }
            return $this->successResponse(new TagResource($tag));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try {
            $validated = $request->validated();

            $tag->update([
                'name' => $request->name ?? $tag->name,
                'language_id' => $request->language_id ?? $tag->language_id,
            ]);
            return $this->successResponse(new TagResource($tag));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();
            return $this->successResponse(new TagResource($tag),$message='deleted done');
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
