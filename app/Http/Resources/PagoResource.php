<?php

namespace App\Http\Resources;

use App\Models\Toma;
use App\Models\Usuario;
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
            'dueno' => $this->formatDueno(),
            'usuario' => $this->formatUsuario(),
            "id_corte_caja" => $this->id_corte_caja,
            // ticket
            "total_pagado" => $this->total_pagado,
            //
            "total_abonado" => $this->total_abonado,
            "saldo_anterior" => $this->saldo_anterior,
            "saldo_actual" => $this->saldo_pendiente,
            "saldo_no_aplicado" => $this->saldo_a_favor,
            "recibido" => $this->recibido,
            "cambio" => $this->cambio,
            //
            "forma_pago" => $this->forma_pago,
            "fecha_pago" => $this->fecha_pago,
            "estado" => $this->estado,
            "timbrado" => $this->cfdi,
            "referencia" => $this->referencia,
            "abonos" => AbonoResource::collection($this->whenLoaded('abonos')),
            "cargos" => CargoResource::collection($this->whenLoaded('cargos')),
            'abonos_con_cargos' => AbonoResource::collection($this->whenLoaded('abonosConCargos')),
            //"saldo_anterior" => number_format($this->saldo_anterior, 2, '.', '') ? $this->saldo_anterior : 0,
            //"saldo_actual" => number_format($this->saldo_actual, 2, '.', '') ? $this->saldo_actual : 0,
            //"saldo_no_aplicado" => number_format($this->saldo_no_aplicado, 2, '.', '') ? $this->saldo_no_aplicado : 0,
            //"total_abonado" => number_format($this->total_abonado(), 2, '.', ''),
            //,"bonificaciones" => AbonoResource::collection($this->whenLoaded('bonificaciones')),
        ];
    }
}
