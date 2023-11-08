<?php

namespace App\Http\Controllers\API;

use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SocialResource;
use App\Http\Requests\StoreSocialRequest;
use App\Http\Requests\UpdateSocialRequest;
use App\Traits\APIResponseTrait;

class SocialController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $link = SocialMedia::all();
            return $this->successResponse(SocialResource::collection($link));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocialRequest $request)
    {
        try {
            $validated = $request->validated();

            $link = SocialMedia::create([
                'name' => $request->name,
                'link' => $request->link,
            ]);
            return $this->successResponse(new SocialResource($link));
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
            $link = SocialMedia::findOrFail($id);
            return $this->successResponse(new SocialResource($link));
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialRequest $request, String $id)
    {
        try {
            $validated = $request->validated();

            $link = SocialMedia::findOrFail($id);

            $link->update([
                'name' => $request->name,
                'link' => $request->link,
            ]);
            return $this->successResponse(new SocialResource($link));
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
            $link = SocialMedia::findOrFail($id);

            $link->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse($th);
        }
    }
}
