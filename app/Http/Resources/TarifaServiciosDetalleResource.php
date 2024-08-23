<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifaServiciosDetalleResource extends JsonResource
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
            "id_tarifa_servicio" => $this->id_tarifa_servicio,
            "rango" => $this->rango,
            "monto" => $this->monto,
        ];
    }
}
