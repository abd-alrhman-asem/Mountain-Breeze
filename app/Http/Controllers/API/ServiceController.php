<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\APIResponseTrait;

class ServiceController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $service = Service::all();
            return $this->successResponse(ServiceResource::collection($service));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no services');
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

            return $this->successResponse(new ServiceResource($service));

        } catch (\Throwable $th) {
            return $this->FailResponse('create not done');
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
            return $this->FailResponse('there is no service');
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

           $service = Service::create([
            'name'=>$request->name??$service->name,
            'lang'=>$request->lang??$service->lang,
           ]);

            return $this->successResponse(new ServiceResource($service));

        } catch (\Throwable $th) {
            return $this->FailResponse('update not done');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no service to delete');
        }
    }
}
