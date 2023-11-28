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
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['store','show']]);
    }
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
            $args['data'] = BookingResource::collection($bookings);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
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
            $args['message'] = 'booking stored successfully ';
            $args['data'] = new BookingResource($booking);
            return $this->successResponse($args , 200 );
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $args['data'] = new BookingResource($booking);
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
            $booking = Booking::findOrFail($id);
            $booking->delete();
            $args['message'] = 'booking deleted successfully ';
            return $this->successResponse($args , 200);
        } catch (\Throwable $th) {
            return $this->FailResponse($th->getMessage());
        }
    }
}
