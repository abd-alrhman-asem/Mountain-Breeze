<?php

namespace App\Http\Controllers\API;

use App\Models\Service;
use App\Models\Language;
use App\Traits\UploadImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
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
            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                $service = Service::where('language_id', '=', $language->id)->get();
                return $this->successResponse(ServiceResource::collection($service));
            }
            $service = Service::all();
            return $this->successResponse(ServiceResource::collection($service));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $service = Service::create([
                'name' => $request->name,
                'language_id' => $request->language_id,
            ]);
            if ($request->hasFile('images')) {
                $get_images = $request->file('images');
                foreach ($get_images as $image) {
                    $file_name = $this->StoreImage($image, 'public/Services');
                    $service->images()->create(['url' => $file_name]);
                }
            }
            return $this->successOperationResponse('service stored successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request): JsonResponse
    {
        try {
            $service = Service::find($id);
            if (!$service)
                return $this->errorResponse('there is no service for this id ');
            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                if ($language->id != $service->language_id)
                    return $this->errorResponse('this service for another language');
            }
            return $this->successResponse(new ServiceResource($service));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, string $id): JsonResponse
    {
        try {
            $service = Service::find($id);
            if (!$service)
                return $this->errorResponse('there is no service for this id ');

            if ($request->hasFile('images')) {
                foreach ($service->images as $image) {
                    $this->DeleteImage('public/Services', $image);
                }
                $get_images = $request->file('images');
                foreach ($get_images as $image) {
                    $file_name = $this->StoreImage($image, 'public/Services');
                    $service->images()->create(['url' => $file_name]);
                }
            }
            $service->update([
                'name' => $request->name ?? $service->name,
                'language_id' => $request->language_id ?? $service->language_id,
            ]);
            return $this->successOperationResponse('service upload successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $service = Service::find($id);
            if (!$service)
                return $this->errorResponse('there is no service for this id ');
            foreach ($service->images as $image) {
                $this->DeleteImage('public/Services', $image);
            }
            $service->delete();
            return $this->successResponse('service deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
