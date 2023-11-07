<?php

namespace App\Http\Controllers\API;

use App\Models\General;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Http\Requests\StoreGeneralRequest;
use App\Http\Requests\UpdateGeneralRequest;

class GeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $information = General::all();
        return response()->json(GeneralResource::collection($information), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGeneralRequest $request)
    {
        $validated = $request->validated();

        $information =   General::create([
            'name' => $request->name,
            'value' => $request->value,
            'lang' => $request->lang,
        ]);
        return response()->json(new GeneralResource($information), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $information = General::findOrFail($id);
        return response()->json(new GeneralResource($information), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGeneralRequest $request, General $general)
    {
        $validated = $request->validated();


        $general->update([
            'name' => $request->name??$general->name,
            'value' => $request->value??$general->value,
            'lang' => $request->lang??$general->lang,
        ]);

        return response()->json(new GeneralResource($general), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(General $general)
    {
        $general->delete();
        return response()->json('Deleted Done', 200);
    }
}
