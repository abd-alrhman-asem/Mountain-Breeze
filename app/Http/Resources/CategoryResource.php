<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'=>$this->name,
            'summary'=>$this->summary,
            'childern_categories'=>CategoryResource::collection($this->subCategories),
            'posts'=> PostResource::collection($this->posts),

            'images' => $this->images,
        ];
    }
}
