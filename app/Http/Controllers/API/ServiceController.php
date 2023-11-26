<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\APIResponseTrait;
use App\Traits\UploadImage;

class ServiceController extends Controller
{
    use APIResponseTrait,UploadImage;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $service = Service::all();
            return $this->successResponse(ServiceResource::collection($service));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        try {
            $validated = $request->validated();

           $service = Service::create([
            'name'=>$request->name,
            'lang'=>$request->lang,
           ]);

           $get_images = $request->file('images');
           foreach($get_images as $image){
               $file_name  = $this->StoreImage($image,'public/Services');
               $service->images()->create(['url'=>$file_name]);
           }

            return $this->successResponse(new ServiceResource($service));

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
            $service = Service::findOrFail($id);
            return $this->successResponse(new ServiceResource($service));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $service = Service::findOrFail($id);
            $path = 'public/Services';
            foreach($service->images as $image){
                $this->DeleteImage($path,$image);
               }

           $service = Service::create([
            'name'=>$request->name??$service->name,
            'lang'=>$request->lang??$service->lang,
           ]);

           $get_images = $request->file('images');
           foreach($get_images as $image){
               $file_name  = $this->StoreImage($image,'public/Services');
               $service->images()->create(['url'=>$file_name]);
           }

            return $this->successResponse(new ServiceResource($service));

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
            $service = Service::findOrFail($id);
            $path = 'public/Services';
            foreach($service->images as $image){
                $this->DeleteImage($path,$image);
               }
            $service->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
