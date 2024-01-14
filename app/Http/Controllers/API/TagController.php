<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use App\Models\Language;
use App\Traits\APIResponseTrait;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use APIResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                $tags = Tag::where('language_id', '=', $language->id)->get();
                return $this->successResponse(TagResource::collection($tags));
            }
            if (!$tags = Tag::all())
                return $this->errorResponse('there are no tags ');
            return $this->successResponse(TagResource::collection($tags));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    public function store(StoreTagRequest $request):JsonResponse
    {
        try {

            Tag::create([
                'name' => $request->name,
                'language_id' => $request->language_id,
            ]);
            return $this->successOperationResponse('tag stored successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag, Request $request): JsonResponse
    {
        try {
            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                if ($language->id != $tag->language_id) {
                    return $this->errorResponse('this tag for another language');
                }
            }
            return $this->successResponse(new TagResource($tag));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        try {

            $tag->update([
                'name' => $request->name ?? $tag->name,
                'language_id' => $request->language_id ?? $tag->language_id,
            ]);
            return $this->successOperationResponse( 'tag updated successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): JsonResponse
    {
        try {

            $tag->delete();
            return $this->successOperationResponse('tag deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
