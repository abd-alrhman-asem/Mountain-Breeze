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
            'title'      => $this->title,
            'summary'    => $this->summary,
            'description'=>$this->description,
            'language'   => $this->langauges->name,
            'created_at' =>$this->created_at,
            'images'     => $this->images->pluck('url'),
            'videos'     => $this->videos->pluck('video'),
            'tags'       => $this->tags->pluck('name'),
        ];
    }
}
