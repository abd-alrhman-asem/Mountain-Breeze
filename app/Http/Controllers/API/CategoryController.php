<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Language;
use App\Traits\UploadImage;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use APIResponseTrait, UploadImage;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            //$categories = Category::all();
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $categories = Category::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id)
                        ->whereNull('category_id')
                        ->with('subCategories');
                })->get();
            } $args['data'] = CategoryResource::collection($categories);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create([
                'name'        => $request->name,
                'summary'     => $request->summary,
                'language_id' => $request->language_id,
                'category_id' => $request->category_id,
            ]);
            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Category');
                $category->images()->create(['url' => $file_name]);
            }
            $args['message'] = 'category stored successfully ';
            $args['data'] = new CategoryResource($category);
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
            $category = Category::findOrFail($id);
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                if ($language->id == $category->language_id) {
                    $category = Category::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id)
                            ->whereNull('category_id')
                            ->with('subCategories');
                    })->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }
            $args['data'] = new CategoryResource($category);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $path = 'public/Category';
            foreach ($category->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $category->update([
                'name'       => $request->name       ?? $category->name,
                'summary'    => $request->summary    ?? $category->summary,
                'language_id' => $request->language_id      ?? $category->language_id,
                'category_id' => $request->category_id ?? $category->category_id,
            ]);
            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Category');
                $category->images()->create(['url' => $file_name]);
            } $args['message'] = 'category updated successfully ';
            $args['data'] = new CategoryResource($category);
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
            $category = Category::findOrFail($id);
            $path = 'public/Category';
            foreach ($category->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $category->delete();
            $args['message'] = 'category deleted successfully ';
            return $this->successResponse($args , 200);
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
