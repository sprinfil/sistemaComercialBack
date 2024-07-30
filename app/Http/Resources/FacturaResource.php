<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacturaResource extends JsonResource
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
            "id_periodo" => $this->id_periodo,
            "id_toma" => $this->id_toma,
            "id_consumo" => $this->id_consumo,
            "monto" => $this->monto,
            "fecha" => $this->fecha,
        ];
    }
}
