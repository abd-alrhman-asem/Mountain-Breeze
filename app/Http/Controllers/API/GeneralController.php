<?php

namespace App\Http\Controllers\API;

use App\Models\General;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreGeneralRequest;
use App\Http\Requests\UpdateGeneralRequest;

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
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $filename = time() . '.' .  $icon->getClientOriginalExtension();
                $icon->storeAs('public/images', $filename);
                $information->icon = $filename;
                $information->save();
            }

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
        try{
            $general = General::findOrFail($id);

            if ($request->has('icon')) {
                Storage::delete('public/images/' . $general->icon);
                $icon = $request->file('icon');
                $filename = time() . '.' .  $icon->getClientOriginalExtension();
                $icon->storeAs('public/images', $filename);
                $general->icon = $filename;
            }
            $general->update([
                'name' => $request->name,
                'value' => $request->value,
                'lang' => $request->lang,
                'icon' => $filename,
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
            Storage::delete('public/images/' . $general->icon);
            $general->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no general to delete');
        }
    }
}
