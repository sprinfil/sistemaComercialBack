<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class cargoDirectoResource extends JsonResource
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
            "id_cargo" => $this->id_cargo,
            "id_origen" => $this->id_origen,
            "modelo_origen" => $this->modelo_origen,
            "id_dueño" => $this->id_dueño,
            "modelo_dueño" => $this->modelo_dueño,
            "monto" => $this->monto,
            "estado" => $this->estado,            
            "fecha_cargo" => $this->fecha_cargo,
            "fecha_liquidacion" => $this->fecha_liquidacion,
        ];
    }
}
