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
            'name'           =>$this->name,
            'description'    =>$this->description,
            'summary'        =>$this->summary,
            'price_per_night'=>$this->price_per_night,
            'guest_number'   =>$this->guest_number,
            'location'       =>$this->location,
            'language'=> new LanguageResource($this->langauges),
            'room_type_id'   => new RoomTypeResource($this->types),
            'services'       => ServiceResource::collection($this->services),
            'images'     => $this->images,
        ];
    }
}
