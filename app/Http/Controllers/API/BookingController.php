<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Traits\APIResponseTrait;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use APIResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $bookings = Booking::all();

            if ($request->has('created_at')) {
                $bookings = Booking::where('created_at', '=', $request->created_at)->get();
            }

            if ($request->has('guest_number')) {
                $bookings = Booking::where('guest_number', '=', $request->guest_number)->get();
            }

            if ($request->has('room_type_id')) {
                $bookings = Booking::where('room_type_id', '=', $request->room_type_id)->get();
            }

            return $this->successResponse(BookingResource::collection($bookings));
        } catch (\Throwable $th) {
            return $this->FailResponse('there are no bookings');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            $validated = $request->validated();
            $booking = Booking::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'description' => $request->description,
                'guest_number' => $request->guest_number,
                'room_type_id' => $request->room_type_id,
            ]);
            return $this->successResponse(new BookingResource($booking));
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
            $booking = Booking::findOrFail($id);
            return $this->successResponse(new BookingResource($booking));
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no booking');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();
            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->FailResponse('there is no booking to delete');
        }
    }
}
