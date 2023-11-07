<?php

namespace App\Http\Controllers\API;

use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SocialResource;
use App\Http\Requests\StoreSocialRequest;
use App\Http\Requests\UpdateSocialRequest;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $link = SocialMedia::all();
        return response()->json(SocialResource::collection($link), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocialRequest $request)
    {
        $validated = $request->validated();

        $link = SocialMedia::create([
            'name' => $request->name,
            'link' => $request->link,
        ]);

        return response()->json(new SocialResource($link), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $link = SocialMedia::findOrFail($id);

        return response()->json(new SocialResource($link), 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialRequest $request, String $id)
    {
        $validated = $request->validated();

        $link = SocialMedia::findOrFail($id);

        $link->update([
            'name' => $request->name,
            'link' => $request->link,
        ]);

        return response()->json(new SocialResource($link), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $link = SocialMedia::findOrFail($id);

        $link->delete();

        return response()->json('Deleted Done', 200);

    }
}
