<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdenTrabajoConfResource extends JsonResource
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
            "id_tarifa" => $this->id_tarifa,
            "id_tipo_toma" => $this->id_tipo_toma,
            "id_concepto" => $this->id_concepto,
            "nombre_concepto" => $this->concepto->nombre,
            "monto" => $this->monto,
        ];
    }
}
