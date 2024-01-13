<?php

namespace App\Http\Controllers\API;

use App\Models\Language;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomTypeResource;
use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;

class RoomTypeController extends Controller
{
    use APIResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request):JsonResponse
    {
        try {
            $type = RoomType::all();
            if ($request->header('language')) {
                $language = Language::where('name', '=',$request->header('language'))->first();
                $type = RoomType::where('language_id', '=', $language->id)->get();
            }
            return $this->successResponse(RoomTypeResource::collection($type) );
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomTypeRequest $request): JsonResponse
    {
        try {
            RoomType::create([
                'name' => $request->name,
                'language_id' => $request->language_id,
            ]);
            return $this->successOperationResponse('Room Type stored successfully ' );
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
            $roomType = RoomType::find($id);
            if (!$roomType)
                return $this->errorResponse('there are no roomType for this id ');
            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();

                if ($language->id != $roomType->language_id) {
                    return $this->errorResponse('this RoomType is for another language ');
                }
            }
            return $this->successResponse(new RoomTypeResource($roomType) );
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomTypeRequest $request, string $id): JsonResponse
    {
        try {
            $roomType = RoomType::find($id);
            if (!$roomType)
                return $this->errorResponse('there are no roomType for this id ');
            $roomType->update([
                'name' => $request->name ?? $roomType->name,
                'language_id' => $request->language_id ?? $roomType->language_id,
            ]);
            return $this->successOperationResponse('Room Type updated successfully ' );
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $roomType = RoomType::find($id);
            if (!$roomType)
                return $this->errorResponse('there are no roomType for this id ');
            $roomType->delete();
            return $this->successOperationResponse('room type  deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
