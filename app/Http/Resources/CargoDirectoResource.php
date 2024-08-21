<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CargoDirectoResource extends JsonResource
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
            "id_origen" => $this->id_origen,
            "modelo_origen" => $this->modelo_origen,
            "cargos" =>TarifaConceptoDetalleResource::collection($this->whenLoaded('cargos'))
        ];
    }
}
