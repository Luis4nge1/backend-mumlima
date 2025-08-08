<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityTypeResource extends JsonResource
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
            'entities_count' => $this->whenCounted('entities'),
            'entities' => EntityResource::collection($this->whenLoaded('entities')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}