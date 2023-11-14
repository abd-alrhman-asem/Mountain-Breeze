<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use App\Traits\APIResponseTrait;

class LanguageController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $lang = Language::all();
            return $this->successResponse(LanguageResource::collection($lang));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLanguageRequest $request)
    {
        try {
            $validate =$request->validated();
            $lang = Language::create([
                'name'=>$request->name,
            ]);
            return $this->successResponse(new LanguageResource($lang));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $lang = Language::findOrFail($id);
            return $this->successResponse(new LanguageResource($lang));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageRequest $request, string $id)
    {
        try {
            $validate =$request->validated();
            $lang = Language::findOrFail($id);
            $lang ->update([
                'name'=> $request->name ??$lang->name,
            ]);
            return $this->successResponse(new LanguageResource($lang));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $lang = Language::findOrFail($id);
            $lang->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }
}
