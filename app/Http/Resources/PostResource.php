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
            'title'       =>$this->title,
            'summary'     =>$this->summary,
            'description' =>$this->description,
            'images'      => $this->images,
            'videos'      => $this->videos,
            'created_at'  =>$this->created_at,
            'language'=> new LanguageResource($this->langauges),
            //'category'    =>new CategoryResource($this->category),
        ];
    }
}
