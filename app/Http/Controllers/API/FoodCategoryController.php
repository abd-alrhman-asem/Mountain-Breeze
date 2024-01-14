<?php

namespace App\Http\Controllers\API;

use App\Models\Language;
use App\Models\FoodCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodCategoryResource;
use App\Http\Requests\StoreFoodCategoryRequest;
use App\Http\Requests\UpdateFoodCategoryRequest;

class FoodCategoryController extends Controller
{
    use APIResponseTrait;

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
            $food_cat = FoodCategory::all();
            if ($request->header('language')) {
                if (!$language = Language::where('name', '=', $request->header('language'))->first())
                    return $this->errorResponse('there are no language for this name');
                $food_cat = FoodCategory::where('language_id', '=', $language->id)->get();
            }
            return $this->successResponse(FoodCategoryResource::collection($food_cat));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodCategoryRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            FoodCategory::create([
                'name' => $request->name,
                'summary' => $request->summary,
                'language_id' => $request->language_id,
            ]);
            return $this->successOperationResponse('food category created successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request): JsonResponse
    {
        try {
            $food_category = FoodCategory::find($id);
            if (!$food_category)
                return $this->errorResponse('there are no food category for this id  ');
            if ($request->header('language')) {
                if (!$language = Language::where('name', '=', $request->header('language'))->first())
                    return $this->errorResponse('there are no language for this name');
                if ($language->id != $food_category->language_id) {
                    return $this->errorResponse('this food category is in another language');
                }
            }
            return $this->successResponse(new FoodCategoryResource($food_category));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodCategoryRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();

            $food_category = FoodCategory::find($id);
            if (!$food_category)
                return $this->errorResponse('there are mo food category on this id ');

            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                if ($language->id != $food_category->language_id) {
                    return $this->errorResponse('this food category is in another language');
                }
            }
            $food_category->update([
                'name' => $request->name ?? $food_cat->name,
                'summary' => $request->summary ?? $food_cat->summary,
                'language_id' => $request->language_id ?? $food_cat->language_id,
            ]);
            return $this->successOperationResponse('food category updated successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (!$food_cat = FoodCategory::find($id))
                return $this->errorResponse('there are no food category for this id ');

            $food_cat->delete();
            return $this->successOperationResponse('food category deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
