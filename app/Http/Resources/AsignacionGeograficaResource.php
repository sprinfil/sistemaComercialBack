<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsignacionGeograficaResource extends JsonResource
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
            "id" => $this->id,
            "id_modelo" => $this->id_modelo,
            "estatus" => $this->estatus,
            "punto" => PuntoResource::collection($this->whenLoaded('punto')),
        ];
    }
}
