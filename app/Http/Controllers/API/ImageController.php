<?php

namespace App\Http\Controllers\API;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Requests\StoreImageRequest;
use App\Traits\UploadImage;

class ImageController extends Controller
{
    use APIResponseTrait,UploadImage;
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try{
            $images = Image::paginate(14);
            if ($request->has('category_id')) {
                $images = Image::where('category_id', '=', $request->category_id)->paginate(14);
            }

            return $this->successResponse(ImageResource::collection( $images)) ;
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $request): JsonResponse
    {
        try{
            $file_name  = $this->StoreImage($request->url, 'public/Images/Dashboard');
             Image::create([
                'url'      =>$file_name,
                'category_id'=>$request->category_id,
            ]);
            return $this->successOperationResponse('images stored successfully');

        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
