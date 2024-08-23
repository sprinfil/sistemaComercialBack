<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CargoResource extends JsonResource
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
            "id_concepto" => $this->id_concepto,
            "nombre" => $this->nombre,
            "id_origen" => $this->id_origen,
            "modelo_origen" => $this->modelo_origen,
            "id_dueno" => $this->id_dueno,
            "modelo_dueno" => $this->modelo_dueno,
            "concepto" => $this->concepto,
            "monto" => $this->monto,
            "iva" => $this->iva,
            "estado" => $this->estado,
            "id_convenio" => $this->id_convenio,
            "fecha_cargo" => $this->fecha_cargo,
            "fecha_liquidacion" => $this->fecha_liquidacion,
        ];
    }
}
