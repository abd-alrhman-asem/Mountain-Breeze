<?php

namespace App\Http\Controllers\API;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Traits\APIResponseTrait;
use App\Traits\UploadImage;

class RoomController extends Controller
{
    use APIResponseTrait,UploadImage;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
           $rooms = Room::all();

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

            return $this->successResponse(RoomResource::collection($rooms));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no rooms');
        }
    }
        /**
     * Display a listing of the resource.
     */
    public function deleted_rooms()
    {
        try {
           $rooms = Room::onlyTrashed()->get();
            return $this->successResponse(RoomResource::collection($rooms));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no rooms');
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
                'price_per_night'=> $request->price_per_night,
                'guest_number'   => $request->guest_number,
                'location'       => $request->location,
                'room_type_id'   => $request->room_type_id,
            ]);

            $room->services()->attach($request->services);

            $get_images = $request->file('images');
            foreach($get_images as $image){
                $file_name  = $this->StoreImage($image,'public/Rooms');
                $room->images()->create(['url'=>$file_name]);
            }

             return $this->successResponse(new RoomResource($room));
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
            $room = Room::findOrFail($id);
             return $this->successResponse(new RoomResource($room));
         } catch (\Throwable $th) {
             return $this->FailResponse('there is no room');
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
            foreach($room->images as $image){
                $this->DeleteImage($path,$image);
               }
            $room->update([
                'name'           => $request->name           ??$room->name,
                'description'    => $request->description    ??$room->description,
                'summary'        => $request->summary        ??$room->summary,
                'price_per_night'=> $request->price_per_night??$room->price_per_night,
                'guest_number'   => $request->guest_number   ??$room->guest_number,
                'location'       => $request->location       ??$room->location,
                'room_type_id'   => $request->room_type_id   ??$room->room_type_id,
            ]);
            $room->services()->sync($request->services);
            $get_images = $request->file('images');
            foreach($get_images as $image){
                $file_name  = $this->StoreImage($image,'public/Rooms');
                $room->images()->create(['url'=>$file_name]);
            }
             return $this->successResponse(new RoomResource($room));
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
            $room = Room::findOrFail($id);
            $path = 'public/Rooms';
            foreach($room->images as $image){
                $this->DeleteImage($path,$image);
               }
            $room->services()->detach();
            $room->delete();
             return $this->successResponse();
         } catch (\Throwable $th) {
             return $this->FailResponse('there is no room to delete');
         }
    }
}
