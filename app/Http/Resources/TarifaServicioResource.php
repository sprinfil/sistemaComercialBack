<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifaServicioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "id_tarifa" => $this->id_tarifa,
            "id_tipo_toma" => $this->id_tipo_toma,
            "genera_iva" => $this->genera_iva,
            "tipo_servicio" => $this->tipo_servicio,
        ];
    }
}
