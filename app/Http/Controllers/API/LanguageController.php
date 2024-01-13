<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{


    use APIResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

        try {
            $languages = Language::all();
            if (!$languages) {
                return $this->errorResponse('there are no languages.');
            }

            return $this->successResponse(LanguageResource::collection($languages));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLanguageRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            Language::create([
                'name' => $request->name,
            ]);
            return $this->successOperationResponse("language created successfully ");
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            if (!$id)
                return $this->errorResponse(' please enter the id ');
            if (!$language = Language::find($id))
                return $this->errorResponse('there are no language for this id ');
            return $this->successResponse(new LanguageResource($language));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageRequest $request, string $id): JsonResponse
    {
        try {
            if (!$id)
                return $this->errorResponse(' please enter the id ');
            if (!$language = Language::find($id))
                return $this->errorResponse('there are no language for this id ');

            $language->update([
                'name' => $request->name ?? $language->name,
            ]);
            return $this->successOperationResponse("language updated successfully ");
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
            if (!$id)
                return $this->errorResponse(' please enter the id ');
            if (!$language = Language::find($id))
                return $this->errorResponse('there are no language for this id ');
            $language->delete();
            return $this->successOperationResponse("the language deletes successfully");
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());

        }
    }
}
