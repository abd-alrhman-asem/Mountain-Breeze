<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'summary' => $this->summary,
            'price_per_night' => $this->price_per_night,
            'guest_number' => $this->guest_number,
            'location' => $this->location,
            'language' => $this->langauges->name,
            'room_type_id' => $this->types->name,
            'services' => $this->services->pluck('name'),
            'images' => $this->images->pluck('url'),
        ];
    }
}
