<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Http\Resources\FoodResource;
use App\Models\Food;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Traits\UploadImage;

class FoodController extends Controller
{
    use APIResponseTrait,UploadImage;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $food = Food::all();

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

            return $this->successResponse(FoodResource::collection($food));
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
                'description'     =>$request->description,
                'lang'            =>$request->lang,
                'food_category_id'=>$request->food_category_id,
            ]);
            $get_images = $request->file('images');
            foreach($get_images as $image){
                $file_name  = $this->StoreImage($image,'public/Foods');
                $food->images()->create(['url'=>$file_name]);
            }
            return $this->successResponse(new FoodResource($food));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $food = Food::findOrFail($id);
            return $this->successResponse(new FoodResource($food));
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
            foreach($food->images as $image){
                $this->DeleteImage($path,$image);
               }
            $food->update([
                'title'           => $request->title          ??$food->title,
                'description'     =>$request->description     ??$food->description,
                'lang'            =>$request->lang            ??$food->lang,
                'food_category_id'=>$request->food_category_id??$food->food_category_id,
            ]);
            $get_images = $request->file('images');
            foreach($get_images as $image){
                $file_name  = $this->StoreImage($image,'public/Foods');
                $food->images()->create(['url'=>$file_name]);
            }
            return $this->successResponse(new FoodResource($food));
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
            foreach($food->images as $image){
                $this->DeleteImage($path,$image);
               }
            $food->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
