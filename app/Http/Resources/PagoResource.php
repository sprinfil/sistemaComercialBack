<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PagoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "folio" => $this->folio,
            "id_caja" => $this->id_caja,
            "id_dueno" => $this->id_dueno,
            "modelo_dueno" => $this->modelo_dueno,
            "id_corte_caja" => $this->id_corte_caja,
            "total_pagado" => $this->total_pagado,
            "forma_pago" => $this->forma_pago,
            "fecha_pago" => $this->fecha_pago,
            "estado" => $this->estado,
            "timbrado" => $this->timbrado,
            "total_abonado" => $this->total_abonado,
            "abonos" => AbonoResource::collection($this->whenLoaded('abonos')),
            "cargos" => CargoResource::collection($this->whenLoaded('cargos'))
            //,"bonificaciones" => AbonoResource::collection($this->whenLoaded('bonificaciones')),
        ];
    }
}
