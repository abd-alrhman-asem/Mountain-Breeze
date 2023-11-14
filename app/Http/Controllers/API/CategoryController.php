<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\APIResponseTrait;

class CategoryController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return $this->successResponse(CategoryResource::collection($categories));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create([
                'name' => $request->name,
                'summary'=>$request->summary,
                'lang'=>$request->lang,
            ]);
            return $this->successResponse(new CategoryResource($category));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            return $this->successResponse(new CategoryResource($category));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category ->update([
                'name'   => $request->name    ??$category->name,
                'summary'=> $request->summary ??$category->summary,
                'lang'   => $request->lang    ??$category->lang,
            ]);
            return $this->successResponse(new CategoryResource($category));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }
}
