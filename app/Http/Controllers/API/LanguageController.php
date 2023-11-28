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
            $args['data'] = LanguageResource::collection($languages);
            return $this->successResponse( $args ,200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLanguageRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validate =$request->validated();
            $language = Language::create([
                'name'=>$request->name,
            ]);
            $args['data'] = new LanguageResource($language);
            $args['message'] = "language created successfully ";
            return $this->successResponse( $args ,200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $language = Language::findOrFail($id);
            $args['data'] = new LanguageResource($language);
            return $this->successResponse( $args ,200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validate =$request->validated();
            $language = Language::findOrFail($id);
            $language->update([
                'name'=> $request->name ??$language->name,
            ]);
            $args['data'] = new LanguageResource($language);
            $args['message'] = "language updated successfully ";
            return $this->successResponse( $args ,200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $language = Language::findOrFail($id);
            $language->delete();
            $args['message']= "the language deletes successfully";
            return $this->successResponse( $args ,200) ;
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
