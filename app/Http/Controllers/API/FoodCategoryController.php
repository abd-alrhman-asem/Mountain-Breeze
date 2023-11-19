<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFoodCategoryRequest;
use App\Http\Requests\UpdateFoodCategoryRequest;
use App\Http\Resources\FoodCategoryResource;
use App\Models\FoodCategory;
use App\Traits\APIResponseTrait;

class FoodCategoryController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $food_cat = FoodCategory::all();
            return $this->successResponse(FoodCategoryResource::collection($food_cat));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no food categories');
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
                'name' =>$request->name,
                'summary'=> $request->summary,
                'lang' =>$request->lang,
            ]);
            return $this->successResponse(new FoodCategoryResource($food_cat));
        } catch (\Throwable $th) {
            return $this->FailResponse('create not done');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $food_cat = FoodCategory::findOrFail($id);
            return $this->successResponse(new FoodCategoryResource($food_cat));
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no food category');
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
                'name' => $request->name ??$food_cat->name,
                'summary' => $request->summary??$food_cat->summary,
                'lang' => $request->lang??$food_cat->lang,
            ]);

            return $this->successResponse(new FoodCategoryResource($food_cat));
        } catch (\Throwable $th) {
            return $this->FailResponse('update not done');
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
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no food category to delete');
        }
    }
}
