<?php

namespace App\Http\Controllers\API;

use App\Models\Room;
use App\Models\Language;
use App\Traits\UploadImage;
use Illuminate\Http\Request;
use App\Traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;

class RoomController extends Controller
{
    use APIResponseTrait, UploadImage;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $rooms = Room::all();
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $rooms = Room::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->get();
            }

            if ($request->has('price')) {
                $rooms = Room::where('price_per_night', '=', $request->price)->get();
            }
            if ($request->has('location')) {
                $rooms = Room::where('location', '=', $request->location)->get();
            }

            if ($request->has('guest_number')) {
                $rooms = Room::where('guest_number', '=', $request->guest_number)->get();
            }

            if ($request->has('room_type_id')) {
                $rooms = Room::where('room_type_id', '=', $request->room_type_id)->get();
            }
            $args['data'] = RoomResource::collection($rooms);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function deleted_rooms(Request $request)
    {
        try {
            $rooms = Room::onlyTrashed()->get();
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();

                $rooms = Room::whereHas('langauges', function ($query) use ($language) {
                    $query->where('language_id', '=', $language->id);
                })->get();
            }
            $args['data'] = RoomResource::collection($rooms);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        try {
            $validate = $request->validated();
            $room = Room::create([
                'name'           => $request->name,
                'description'    => $request->description,
                'summary'        => $request->summary,
                'price_per_night' => $request->price_per_night,
                'guest_number'   => $request->guest_number,
                'location'       => $request->location,
                'room_type_id'   => $request->room_type_id,
                'language_id' => $request->language_id,
            ]);

            $room->services()->attach($request->services);

            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Rooms');
                $room->images()->create(['url' => $file_name]);
            }
            $args['message'] = 'room stored successfully ';
            $args['data'] = new RoomResource($room);
            return $this->successResponse($args , 200 );
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
            $room = Room::findOrFail($id);
            if ($request->header('language')) {
                $language_header = $request->header('language');
                $language = Language::where('name', '=', $language_header)->first();
                if ($language->id == $room->language_id) {
                    $room = Room::whereHas('langauges', function ($query) use ($language) {
                        $query->where('language_id', '=', $language->id);
                    })->first();
                } else {
                    return $this->FailResponse('go out');
                }
            }
            $args['data'] = new RoomResource($room);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, string $id)
    {
        try {
            $validate = $request->validated();
            $room = Room::findOrFail($id);
            $path = 'public/Rooms';
            foreach ($room->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $room->update([
                'name'           => $request->name           ?? $room->name,
                'description'    => $request->description    ?? $room->description,
                'summary'        => $request->summary        ?? $room->summary,
                'price_per_night' => $request->price_per_night ?? $room->price_per_night,
                'guest_number'   => $request->guest_number   ?? $room->guest_number,
                'location'       => $request->location       ?? $room->location,
                'room_type_id'   => $request->room_type_id   ?? $room->room_type_id,
                'language_id'    => $request->language_id    ?? $room->language_id,
            ]);
            $room->services()->sync($request->services);
            $get_images = $request->file('images');
            foreach ($get_images as $image) {
                $file_name  = $this->StoreImage($image, 'public/Rooms');
                $room->images()->create(['url' => $file_name]);
            }
            $args['message'] = 'room updated successfully ';
            $args['data'] = new RoomResource($room);
            return $this->successResponse($args , 200 );
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
            $room = Room::findOrFail($id);
            $path = 'public/Rooms';
            foreach ($room->images as $image) {
                $this->DeleteImage($path, $image);
            }
            $room->services()->detach();
            $room->delete();
            $args['message'] = 'Room deleted successfully ';
            return $this->successResponse($args , 200);        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
