<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Language;
use App\Traits\UploadImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
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
            $categories = Category::all();
            if ($request->header('language')) {
                if (!$language = Language::where('name', '=', $request->header('language'))->first())
                    return $this->errorResponse('there are no language for this name');

                $categories = Category::where('language_id', '=', $language->id)
                    ->whereNull('category_id')
                    ->with('subCategories')
                    ->get();
            }
            return $this->successResponse(CategoryResource::collection($categories));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $category = Category::create([
                'name' => $request->name,
                'summary' => $request->summary,
                'language_id' => $request->language_id,
                'category_id' => $request->category_id,
            ]);
            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name = $this->StoreImage($image, 'public/Category');
                $category->images()->create(['url' => $file_name]);
            }
            return $this->successOperationResponse('category created successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        try {
            $category = Category::findOrFail($id);
            if ($request->header('language')) {
                if (!$language = Language::where('name', '=', $request->header('language'))->first())
                    return $this->errorResponse('there are no language for this name');
                if ( (!$language->id == $category->language_id))
                    return $this->errorResponse('this category  is in another language');
            }
            return $this->successResponse(new CategoryResource($category));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id): JsonResponse
    {
        try {
            $category = Category::find($id);
            if (!$category)
                return $this->errorResponse('there are no category to update it ');

            if ( $get_images = $request->file('images')) {
                foreach ($category->images as $image) {
                    $this->DeleteImage('public/Category', $image);
                }
                foreach ($get_images as $image) {
                    $file_name = $this->StoreImage($image, 'public/Category');
                    $category->images()->create(['url' => $file_name]);
                }
            }
            $category->update([
                'name'          => $request->name           ?? $category->name,
                'summary'       => $request->summary        ?? $category->summary,
                'language_id'   => $request->language_id    ?? $category->language_id,
                'category_id'   => $request->category_id    ?? $category->category_id,
            ]);
            return $this->successOperationResponse('category updated successfully ');
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
            $category = Category::find($id);
            if (!$category)
                return $this->errorResponse('there are no category to delete it ');
            foreach ($category->images as $image) {
                $this->DeleteImage('public/Category', $image);
            }
            $category->delete();
            return $this->successOperationResponse('category deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
