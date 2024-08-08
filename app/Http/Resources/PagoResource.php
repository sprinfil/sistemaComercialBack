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
            "id_caja" => $this->id_caja,
            "id_dueño" => $this->id_dueño,
            "modelo_dueño" => $this->modelo_dueño,
            "id_corte_caja" => $this->id_corte_caja,
            "total_pagado" => $this->total_pagado,
            "forma_pago" => $this->forma_pago,
            "fecha_pago" => $this->fecha_pago,
            "estado" => $this->estado,
            "total_abonado" => $this->total_abonado,
            "abonos" => AbonoResource::collection($this->whenLoaded('tarifas'))
            //,"bonificaciones" => AbonoResource::collection($this->whenLoaded('bonificaciones')),
        ];
    }
}
