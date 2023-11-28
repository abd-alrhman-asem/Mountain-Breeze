<?php

namespace App\Http\Controllers\API;

use App\Models\Service;
use App\Models\Language;
use App\Traits\UploadImage;
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
        $this->middleware('auth:api',['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $service = Service::all();
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $service = Service::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->get();
            }
            $args['data'] = ServiceResource::collection($service);
            return $this->successResponse($args , 200 );
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
                'name' => $request->name,
                'language_id' => $request->language_id,
            ]);

            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Services');
                $service->images()->create(['url' => $file_name]);
            }
            $args['message'] = 'service stored successfully ';
            $args['data'] = new ServiceResource($service);
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
            $service = Service::findOrFail($id);
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                if ($language->id == $service->language_id) {
                    $service = Service::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id);
                    })->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }  $args['data'] = new ServiceResource($service);
            return $this->successResponse($args , 200 );
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
            foreach ($service->images as $image) {
                $this->DeleteImage($path, $image);
            }

            $service = Service::create([
                'name' => $request->name ?? $service->name,
                'language_id' => $request->language_id ?? $service->language_id,
            ]);

            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Services');
                $service->images()->create(['url' => $file_name]);
            }
            $args['message'] = 'service upload successfully ';
            $args['data'] = new ServiceResource($service);
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
            $service = Service::findOrFail($id);
            $path = 'public/Services';
            foreach ($service->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $service->delete();
            $args['message'] = 'service deleted successfully ';
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
