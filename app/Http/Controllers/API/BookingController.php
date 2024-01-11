<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\RoomType;
use App\Traits\APIResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use APIResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */


    public function index(Request $request): JsonResponse
    {
        try {

            $bookings = Booking::all();

            if ($request->has('created_at')) {
                if ($request->created_at) {
                    $date = Carbon::parse($request->created_at);
                    $bookings = Booking::whereDate('created_at', '=', $date)->simplePaginate(100);
                    if (!$bookings->items())
                        return $this->errorResponse('there are no booking orders in this date ');
                }
            }

            if ($request->has('guest_number')) {
                $bookings = Booking::where('guest_number', '=', $request->guest_number)->simplePaginate(100);
                if (!$bookings->items())
                    return $this->errorResponse('there are no booking orders for this guest number ');
            }

            if ($request->has('room_type_id')) {
//                return $request->room_type_id;
                if (RoomType::find($request->room_type_id)) {
                    $bookings = Booking::where('room_type_id', '=', $request->room_type_id)->simplePaginate(100);
                    if (!$bookings->items())
                        return $this->errorResponse('there are no booking orders for this room type .');
                }
                else return $this->errorResponse('there are no room type like this ');
            }
            return $this->successResponse(BookingResource::collection($bookings));
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
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
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'check_in'      => $request->check_in,
                'check_out'     => $request->check_out,
                'description'   => $request->description,
                'guest_number'  => $request->guest_number,
                'room_type_id'  => $request->room_type_id,
            ]);
            return $this->successOperationResponse( 'booking stored successfully ');
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
            $booking = Booking::findOrFail($id);
            return $this->successResponse(new BookingResource($booking));
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
            $booking = Booking::findOrFail($id);
            $booking->delete();
            return $this->successOperationResponse('booking deleted successfully');
        } catch (\Throwable $th) {
            return $this->generalFailureResponse($th->getMessage());
        }
    }
}
