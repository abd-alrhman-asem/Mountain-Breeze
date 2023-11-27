<?php

namespace App\Http\Controllers\API;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Requests\StoreImageRequest;
use App\Traits\UploadImage;

class ImageController extends Controller
{
    use APIResponseTrait,UploadImage;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $images = Image::paginate(14);
            if ($request->has('category_id')) {
                $images = Image::where('category_id', '=', $request->category_id)->paginate(14);
            }
            return ImageResource::collection( $images) ;
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $request)
    {
        try{
            $file_name  = $this->StoreImage($request->url, 'public/Images/Dashboard');
            $video = Image::create([
                'url'      =>$file_name,
                'category_id'=>$request->category_id,
            ]);
            return new ImageResource($video);

        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {

    }
}
