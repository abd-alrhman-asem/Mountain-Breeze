<?php

namespace App\Http\Controllers\API;

use App\Models\SocialMedia;
use App\Http\Controllers\Controller;
use App\Http\Resources\SocialResource;
use App\Http\Requests\StoreSocialRequest;
use App\Http\Requests\UpdateSocialRequest;
use App\Traits\APIResponseTrait;
use App\Traits\UploadImage;

class SocialController extends Controller
{
    use APIResponseTrait,UploadImage;
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $link = SocialMedia::all();
            $args['data'] = SocialResource::collection($link);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocialRequest $request)
    {
        try {
            $validated = $request->validated();

            $link = SocialMedia::create([
                'name' => $request->name,
                'link' => $request->link,
            ]);
            $get_images = $request->file('images');
            foreach($get_images as $image){
                $file_name  = $this->StoreImage($image,'public/SocialMedia');
                $link->images()->create(['url'=>$file_name]);
            }
            $args['message'] = 'social media account  stored successfully ';
            $args['data'] = new SocialResource($link);
            return $this->successResponse($args , 200 );
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
            $link = SocialMedia::findOrFail($id);
            $args['data'] = new SocialResource($link);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialRequest $request, String $id)
    {
        try {
            $validated = $request->validated();

            $link = SocialMedia::findOrFail($id);
            $path = 'public/SocialMedia';
            foreach($link ->images as $image){
                $this->DeleteImage($path,$image);
               }

            $link->update([
                'name' => $request->name,
                'link' => $request->link,
            ]);
            $get_images = $request->file('images');
            foreach($get_images as $image){
                $file_name  = $this->StoreImage($image,'public/SocialMedia');
                $link->images()->create(['url'=>$file_name]);
            }
            $args['message'] = 'social media account  updated successfully ';
            $args['data'] = new SocialResource($link) ;
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
            $link = SocialMedia::findOrFail($id);
            $path = 'public/SocialMedia';
            foreach($link ->images as $image){
                $this->DeleteImage($path,$image);
               }
            $link->delete();
            $args['message'] = 'social media account deleted successfully ';
            return $this->successResponse($args , 200);
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
