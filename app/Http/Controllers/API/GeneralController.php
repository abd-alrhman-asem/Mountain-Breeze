<?php

namespace App\Http\Controllers\API;

use App\Models\General;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreGeneralRequest;
use App\Http\Requests\UpdateGeneralRequest;

class GeneralController extends Controller
{
    use APIResponseTrait;

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
            $information = General::query();
            if ($request->header('language')) {
                if (!$language = Language::where('name', '=', $request->header('language'))->first())
                    return $this->errorResponse('there are no language for this name');

                $information->where('language_id', '=', $language->id)->get();
            }
            return $this->successResponse(GeneralResource::collection($information->get()));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGeneralRequest $request): JsonResponse
    {
        try {
            $information = General::create([
                'name' => $request->name,
                'value' => $request->value,
                'language_id' => $request->language_id,
            ]);
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $filename = time() . '.' . $icon->getClientOriginalExtension();
                $icon->storeAs('public/images', $filename);
                $information->icon = $filename;
                $information->save();
            }
            return $this->successOperationResponse('general details  stored successfully ');
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
            $information = General::find($id);
            if(!$information)
                return $this->errorResponse('there are no information for this id ');
            if ($request->header('language')) {
                if (!$language = Language::where('name', '=', $request->header('language'))->first())
                    return $this->errorResponse('there are no language for this name');
                if ($language->id != $information->language_id)
                    return  $this->errorResponse('this information is for another language ');
            }
            return $this->successResponse(new GeneralResource($information));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGeneralRequest $request, string $id): JsonResponse
    {
        try {
            $general = General::find($id);
            if (!$general)
                return $this->errorResponse('there are no info for this id ');

            if ($request->has('icon')) {
                Storage::delete('public/images/' . $general->icon);
                $icon = $request->file('icon');
                $filename = time() . '.' . $icon->getClientOriginalExtension();
                $icon->storeAs('public/images', $filename);
                $general->icon = $filename;
            }
            $general->update([
                'name' => $request->name,
                'value' => $request->value,
                'language_id' => $request->language_id,
                'icon' => $filename,
            ]);
            return $this->successOperationResponse('general details  updated successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(General $general)
    {
        try {
            Storage::delete('public/images/' . $general->icon);
            $general->delete();
            return $this->successOperationResponse( 'general details  deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
