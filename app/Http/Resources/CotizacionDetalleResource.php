<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CotizacionDetalleResource extends JsonResource
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
            "id_cotizacion" => $this->id_cotizacion,
            "id_sector" => $this->id_sector,
            "id_concepto" => $this->id_concepto,
            "monto" => $this->monto,
        ];
    }
}
