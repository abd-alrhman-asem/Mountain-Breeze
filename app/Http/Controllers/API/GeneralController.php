<?php

namespace App\Http\Controllers\API;

use App\Models\General;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Http\Requests\StoreGeneralRequest;
use App\Http\Requests\UpdateGeneralRequest;
use App\Traits\APIResponseTrait;

class GeneralController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $information = General::all();
            return $this->successResponse(GeneralResource::collection($information));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no generals');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGeneralRequest $request)
    {
        try {
            $validated = $request->validated();

            $information =   General::create([
                'name' => $request->name,
                'value' => $request->value,
                'lang' => $request->lang,
            ]);
            return $this->successResponse(new GeneralResource($information));
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
            $information = General::findOrFail($id);
            return $this->successResponse(new GeneralResource($information));
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no general');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGeneralRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $general = General::findOrFail($id);
            $general->update([
                'name' => $request->name ?? $general->name,
                'value' => $request->value ?? $general->value,
                'lang' => $request->lang ?? $general->lang,
            ]);
            return $this->successResponse(new GeneralResource($general));
        } catch (\Throwable $th) {
            return $this->FailResponse('update not done');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(General $general)
    {
        try {
            $general->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no general to delete');
        }
    }
}
