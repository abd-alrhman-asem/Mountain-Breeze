<?php

namespace App\Http\Controllers\API;

use App\Models\Food;
use App\Models\Language;
use App\Traits\UploadImage;
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
        $this->middleware('auth:api',['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $food = Food::all();
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $food = Food::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->get();
            }
            if ($request->has('food_category_id')) {
                $food = Food::where('food_category_id', '=', $request->food_category_id)->get();
            }

            if ($request->has('title')) {
                $food = Food::where('title', '=', $request->title)->get();
            }

            if ($request->has('description')) {
                $food = Food::where('description', '=', $request->description)->get();
            }

            if ($request->has('lang')) {
                $food = Food::where('lang', '=', $request->lang)->get();
            }
            $args['data'] = FoodResource::collection($food);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodRequest $request)
    {
        try {
            $validated = $request->validated();
            $food = Food::create([
                'title'           => $request->title,
                'description'     => $request->description,
                'language_id' => $request->language_id,
                'food_category_id' => $request->food_category_id,
            ]);
            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Foods');
                $food->images()->create(['url' => $file_name]);
            }
            $args['message'] = 'food stored successfully ';
            $args['data'] =new FoodResource($food);
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
            $food = Food::findOrFail($id);
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                if ($language->id ==  $food->language_id) {
                    $food = Food::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id);
                    })->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }
            $args['data'] = new FoodResource($food);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $food = Food::findOrFail($id);
            $path = 'public/Foods';
            foreach ($food->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $food->update([
                'title'           => $request->title          ?? $food->title,
                'description'     => $request->description     ?? $food->description,
                'language_id' => $request->language_id           ?? $food->language_id,
                'food_category_id' => $request->food_category_id ?? $food->food_category_id,
            ]);
            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Foods');
                $food->images()->create(['url' => $file_name]);
            }
            $args['message'] = 'food updated successfully ';
            $args['data'] =new FoodResource($food);
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
            $food = Food::findOrFail($id);
            $path = 'public/Foods';
            foreach ($food->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $food->delete();
            $args['message'] = 'food deleted successfully ';
            return $this->successResponse($args , 200);
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
