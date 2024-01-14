<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id ,
            'title'            => $this->title,
            'summary'          => $this->summary,
            'description'      => $this->description,
            'images'           => $this->images->pluck('url'),
            'videos'           => $this->videos->pluck('video'),
            'created_at'       => $this->created_at,
            'language'         => $this->langauges->name,
            'category'         => $this->category->name,
        ];
    }
}
