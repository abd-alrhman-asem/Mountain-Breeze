<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'summary'=> $this->summary,
            'description'=>$this->description,
            'created_at'=>$this->created_at,
            'images'     => $this->images,
            'videos'=> $this->videos,
            'tags'=> TagResource::collection($this->tags),
        ];
    }
}
