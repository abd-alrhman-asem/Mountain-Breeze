<?php

namespace App\Http\Controllers\API;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Language;
use App\Traits\UploadImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;

class FoodController extends Controller
{
    use APIResponseTrait, UploadImage;

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
            $food = Food::query();
            if ($request->header('language')) {
                if (!$language = Language::where('name', '=', $request->header('language'))->first())
                    return $this->errorResponse('there are no language for this name');
            }

            if ($request->has('food_category_id')) {
                if (!$foodCategory = FoodCategory::find($request->food_category_id))
                    return $this->errorResponse('there are no food category foe this id ');
                $food->where('food_category_id', '=', $foodCategory->id)->get();
                if (!$food->exists())
                    return $this->errorResponse('there are no food in this food category ');
            }

            if ($request->has('title')) {
                $food->where('title', 'LIKE', "%" . $request->title . "%")->get();
                if (!$food->exists())
                    return $this->errorResponse('there are no food for this title');
            }

            if ($request->has('description')) {
                $food->where('description', 'LIKE', "%" . $request->description . "%")->get();
                if (!$food->exists())
                    return $this->errorResponse('there are no food for this description');
            }

            return $this->successResponse(FoodResource::collection($food->get()));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $food = Food::create([
                'title' => $request->title,
                'description' => $request->description,
                'language_id' => $request->language_id,
                'food_category_id' => $request->food_category_id,
            ]);
            if (!$get_images = $request->file('images'))
                return $this->errorResponse('please upload image');

            foreach ($get_images as $image) {
                $file_name = $this->StoreImage($image, 'public/Foods');
                $food->images()->create(['url' => $file_name]);
            }
            return $this->successOperationResponse('food stored successfully ');
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
            $food = Food::find($id);
            if (!$food)
                return $this->errorResponse('there are no food for this id');

            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                if ($food->language_id != $language->id)
                    return $this->errorResponse('this food for another language ');
            }

            return $this->successResponse(new FoodResource($food));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodRequest $request, string $id): JsonResponse
    {
        try {
            $food = Food::find($id);
            if (!$food)
                return $this->errorResponse('there are no food for this id');
            $food->update([
                'title' => $request->title ?? $food->title,
                'description' => $request->description ?? $food->description,
                'language_id' => $request->language_id ?? $food->language_id,
                'food_category_id' => $request->food_category_id ?? $food->food_category_id,
            ]);
            if ($get_images = $request->file('images')) {
                foreach ($food->images as $image) {
                    $this->DeleteImage('public/Foods', $image);
                }
                foreach ($get_images as $image) {
                    $file_name = $this->StoreImage($image, 'public/Foods');
                    $food->images()->create(['url' => $file_name]);
                }
            }
            return $this->successOperationResponse('food updated successfully ');
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
            $food = Food::find($id);
            if (!$food)
                return $this->errorResponse('there are no food for this id');
            foreach ($food->images as $image) {
                $this->DeleteImage('public/Foods', $image);
            }
            $food->delete();
            return $this->successOperationResponse('food deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
