<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'guest_number' => $this->guest_number,
            'description' => $this->description,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'order date' => $this->created_at,
            'room type' =>new RoomTypeResource($this->type),
        ];
    }
}
