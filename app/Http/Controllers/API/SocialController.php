<?php

namespace App\Http\Controllers\API;

use App\Models\SocialMedia;
use App\Http\Controllers\Controller;
use App\Http\Resources\SocialResource;
use App\Http\Requests\StoreSocialRequest;
use App\Http\Requests\UpdateSocialRequest;
use App\Traits\APIResponseTrait;
use App\Traits\UploadImage;
use Illuminate\Http\JsonResponse;

class SocialController extends Controller
{
    use APIResponseTrait, UploadImage;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $link = SocialMedia::all();
            return $this->successResponse(SocialResource::collection($link));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocialRequest $request): JsonResponse
    {
        try {
            $link = SocialMedia::create([
                'name' => $request->name,
                'link' => $request->link,
            ]);
            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name = $this->StoreImage($image, 'public/SocialMedia');
                $link->images()->create(['url' => $file_name]);
            }
            return $this->successResponse('social media account  stored successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $link = SocialMedia::find($id);
            if (!$link)
                return $this->errorResponse('there are no data for this id ');
            return $this->successResponse(new SocialResource($link));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialRequest $request, string $id): JsonResponse
    {
        try {
            $link = SocialMedia::find($id);
            if (!$link)
                return $this->errorResponse('there are no data for this id ');
            if ($request->hasFile('images'))    {
                foreach ($link->images as $image) {
                    $this->DeleteImage('public/SocialMedia', $image);
                }
                $get_images = $request->file('images');
                foreach ($get_images as $image) {
                    $file_name = $this->StoreImage($image, 'public/SocialMedia');
                    $link->images()->create(['url' => $file_name]);
                }
            }
            $link->update([
                'name' => $request->name,
                'link' => $request->link,
            ]);
            return $this->successOperationResponse('social media account  updated successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $link = SocialMedia::find($id);
            if (!$link)
                return $this->errorResponse('there are no data for this id ');
            foreach ($link->images as $image) {
                $this->DeleteImage('public/SocialMedia', $image);
            }
            $link->delete();
            return $this->successOperationResponse('social media account deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
