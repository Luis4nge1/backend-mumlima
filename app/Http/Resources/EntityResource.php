<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'type_id' => $this->type_id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'description' => $this->description,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'address' => $this->address,
            'parent' => new EntityResource($this->whenLoaded('parent')),
            'children' => EntityResource::collection($this->whenLoaded('children')),
            'type' => new EntityTypeResource($this->whenLoaded('type')),
            'usuarios_fiscalizacion' => UsuarioFiscalizacionResource::collection($this->whenLoaded('usuariosFiscalizacion')),
            'children_count' => $this->whenCounted('children'),
            'usuarios_fiscalizacion_count' => $this->whenCounted('usuariosFiscalizacion'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}