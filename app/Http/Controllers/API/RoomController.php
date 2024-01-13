<?php

namespace App\Http\Controllers\API;

use App\Models\Room;
use App\Models\Language;
use App\Traits\UploadImage;
use Database\Factories\roomTypeFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    use APIResponseTrait, UploadImage;

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
            $rooms = Room::query();

            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                $rooms->where('language_id', '=', $language->id);
            }
            //return $rooms->get();

            if ($request->has('price')) {
                $rooms->where('price_per_night', '=', $request->price);
            }

            if ($request->has('location')) {
                $rooms->where('location', '=', $request->location);
            }

            if ($request->has('guest_number')) {
                $rooms->where('guest_number', '=', $request->guest_number);
            }

            if ($request->has('room_type_id')) {
                $rooms->where('room_type_id', '=', $request->room_type_id);
            }
//          return  $rooms->get();

            $rooms = $rooms->get();
            if ($rooms->isEmpty())
                return $this->errorResponse('there are no rooms');

            return $this->successResponse(RoomResource::collection($rooms));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function deleted_rooms(Request $request): JsonResponse
    {
        try {
            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                if ($rooms = Room::onlyTrashed()->where('language_id', '=', $language->id)->get())
                    return $this->successResponse(RoomResource::collection($rooms));
            }
            $rooms = Room::onlyTrashed()->get();
            return $this->successResponse(RoomResource::collection($rooms));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request): JsonResponse
    {
        try {
            $room = Room::create([
                'name' => $request->name,
                'description' => $request->description,
                'summary' => $request->summary,
                'price_per_night' => $request->price_per_night,
                'guest_number' => $request->guest_number,
                'location' => $request->location,
                'room_type_id' => $request->room_type_id,
                'language_id' => $request->language_id,
            ]);

            $room->services()->attach($request->services);

            if (!$get_images = $request->file('images'))
                return $this->errorResponse('please upload  images');
            foreach ($get_images as $image) {
                $file_name = $this->StoreImage($image, 'public/Rooms');
                $room->images()->create(['url' => $file_name]);
            }
            return $this->successOperationResponse('room stored successfully ');
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
            $room = Room::find($id);
            if (!$room)
                return $this->errorResponse('there are no room for this id ');
            if ($request->header('language')) {
                $language = Language::where('name', '=', $request->header('language'))->first();
                if ($language->id != $room->language_id) {
                    return $this->errorResponse('this room in another language');
                }
            }
            return $this->successResponse(new RoomResource($room));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, string $id): JsonResponse
    {
        try {
            $room = Room::find($id);
            if (!$room)
                return $this->errorResponse('there are no room for this id ');

            if ($request->hasFile('images')) {
                $path = 'public/Rooms';
                foreach ($room->images as $image) {
                    $this->DeleteImage($path, $image);
                }
                $get_images = $request->file('images');
                foreach ($get_images as $image) {
                    $file_name = $this->StoreImage($image, 'public/Rooms');
                    $room->images()->create(['url' => $file_name]);
                }
            }

            $room->update([
                'name' => $request->name ?? $room->name,
                'description' => $request->description ?? $room->description,
                'summary' => $request->summary ?? $room->summary,
                'price_per_night' => $request->price_per_night ?? $room->price_per_night,
                'guest_number' => $request->guest_number ?? $room->guest_number,
                'location' => $request->location ?? $room->location,
                'room_type_id' => $request->room_type_id ?? $room->room_type_id,
                'language_id' => $request->language_id ?? $room->language_id,
            ]);
            $room->services()->sync($request->services);

            return $this->successResponse('room updated successfully ');
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
            $room = Room::find($id);
            if (!$room)
                return $this->errorResponse('there are no room for this id ');
            foreach ($room->images as $image) {
                $this->DeleteImage('public/Rooms', $image);
            }
            $room->services()->detach();
            $room->delete();
            return $this->successResponse('Room deleted successfully ');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
