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
            $languages = Language::all();
            return $this->successResponse(LanguageResource::collection($languages));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no languages');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLanguageRequest $request)
    {
        try {
            $validate =$request->validated();
            $language = Language::create([
                'name'=>$request->name,
            ]);
            return $this->successResponse(new LanguageResource($language));
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
            $language = Language::findOrFail($id);
            return $this->successResponse(new LanguageResource($language));
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no language');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageRequest $request, string $id)
    {
        try {
            $validate =$request->validated();
            $language = Language::findOrFail($id);
            $language->update([
                'name'=> $request->name ??$language->name,
            ]);
            return $this->successResponse(new LanguageResource($language));
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
            $language = Language::findOrFail($id);
            $language->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no language to delete');
        }
    }
}
