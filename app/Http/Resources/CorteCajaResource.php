<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CorteCajaResource extends JsonResource
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
            "id_caja" => $this->id_caja,
            "id_operador" => $this->id_operador,
            "estatus" => $this->estatus,
            "total_registrado" => $this->total_registrado,
            "total_real" => $this->total_real,
            "total_efectivo_registrado" => $this->total_efectivo_registrado,
            "total_efectivo_real" => $this->total_efectivo_real,
            "total_tarjetas_registrado" => $this->total_tarjetas_registrado,
            "total_tarjetas_real" => $this->total_tarjetas_real,
            "total_cheques_registrado" => $this->total_cheques_registrado,
            "total_cheques_real" => $this->total_cheques_real,
            "discrepancia" => $this->discrepancia,
            "discrepancia_monto" => $this->discrepancia_monto,
            "fecha_corte" => $this->fecha_corte,
        ];
    }
}
