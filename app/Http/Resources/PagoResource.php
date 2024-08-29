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
            "abonos" => AbonoResource::collection($this->whenLoaded('abonos')),
            "cargos" => CargoResource::collection($this->whenLoaded('cargos')),
            "saldo_anterior" => $this->saldo_anterior ? $this->saldo_anterior : 0,
            "saldo_actual" => $this->saldo_actual ? $this->saldo_actual : 0,
            "saldo_no_aplicado" => $this->saldo_no_aplicado ? $this->saldo_no_aplicado : 0,
            "total_abonado" => number_format($this->total_abonado(), 2, '.', ''),
            //,"bonificaciones" => AbonoResource::collection($this->whenLoaded('bonificaciones')),
        ];
    }
}
