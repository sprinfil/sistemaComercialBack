<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConvenioCatalogoResource  extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'conceptos_aplicables' => ConceptoAplicableResource::collection($this->whenLoaded('conceptosAplicables')),
            'convenios' => ConvenioResource::collection($this->whenLoaded('Convenio'))
        ];
    }
}
