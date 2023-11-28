<?php

namespace App\Http\Controllers\API;

use App\Models\Language;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodCategoryResource;
use App\Http\Requests\StoreFoodCategoryRequest;
use App\Http\Requests\UpdateFoodCategoryRequest;

class FoodCategoryController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $food_cat = FoodCategory::all();
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $food_cat = FoodCategory::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->get();
            }
            $args['data']= FoodCategoryResource::collection($food_cat);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodCategoryRequest $request)
    {
        try {
            $validated = $request->validated();
            $food_cat = FoodCategory::create([
                'name' => $request->name,
                'summary' => $request->summary,
                'language_id' => $request->language_id,
            ]);
            $args['data']= new FoodCategoryResource($food_cat);
            $args['message']= 'food category stored successfully ';
            return $this->successResponse($args , 200 );
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
            $food_cat = FoodCategory::findOrFail($id);
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                if ($language->id == $food_cat->language_id) {
                    $food_cat = FoodCategory::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id);
                    })->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }
            $args['data']= new FoodCategoryResource($food_cat);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodCategoryRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $food_cat = FoodCategory::findOrFail($id);
            $food_cat->update([
                'name' => $request->name ?? $food_cat->name,
                'summary' => $request->summary ?? $food_cat->summary,
                'language_id' => $request->language_id ?? $food_cat->language_id,
            ]);
            $args['data']= new FoodCategoryResource($food_cat);
            $args['message']= 'food category updated successfully ';
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $food_cat = FoodCategory::findOrFail($id);
            $food_cat->delete();
            $args['message']= 'food category deleted successfully ';
            return $this->successResponse($args , 200 );        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
