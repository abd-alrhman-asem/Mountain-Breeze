<?php

namespace App\Http\Controllers\API;

use App\Models\RoomType;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;
use App\Http\Resources\RoomTypeResource;

class RoomTypeController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $type = RoomType::all();
            return $this->successResponse(RoomTypeResource::collection($type));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no room types');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomTypeRequest $request)
    {
        try {
            $validated = $request->validated();
            $roomType = RoomType::create([
                'name' => $request->name,
                'lang' => $request->lang,
            ]);
            return $this->successResponse(new RoomTypeResource($roomType));
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
            $type = RoomType::findOrFail($id);
            return $this->successResponse(new RoomTypeResource($type));
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no room type');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomTypeRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $roomType = RoomType::findOrFail($id);
            $roomType->update([
                'name' => $request->name ?? $roomType->name,
                'lang' => $request->lang ?? $roomType->lang,
            ]);
            return $this->successResponse(new RoomTypeResource($roomType));
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
            $type = RoomType::findOrFail($id);
            $type->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no room type to delete');
        }
    }
}
