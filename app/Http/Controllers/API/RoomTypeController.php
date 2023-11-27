<?php

namespace App\Http\Controllers\API;

use App\Models\Language;
use App\Models\RoomType;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomTypeResource;
use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;

class RoomTypeController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $type = RoomType::all();
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $type = RoomType::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->get();
            }
            return $this->successResponse(RoomTypeResource::collection($type));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
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
                'language_id' => $request->language_id,
            ]);
            return $this->successResponse(new RoomTypeResource($roomType));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        try {
            $type = RoomType::findOrFail($id);
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                if ($language->id == $type->language_id) {
                    $type = RoomType::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id);
                    })->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }
            return $this->successResponse(new RoomTypeResource($type));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
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
                'language_id' => $request->language_id ?? $roomType->language_id,
            ]);
            return $this->successResponse(new RoomTypeResource($roomType));
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
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
            return $this->FailResponse($th->getMessage());
        }
    }
}
