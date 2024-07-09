<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatosDomiciliacionResource extends JsonResource
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
            "id_toma" => $this->id_toma,
            "numero_cuenta" => $this->numero_cuenta,
            "fecha_vencimiento" => $this->fecha_vencimiento,
            "tipo_tarjeta" => $this->tipo_tarjeta,
            "limite_cobro" => $this->limite_cobro,
            "domicilio_tarjeta" => $this->domicilio_tarjeta,
        ];
    }
}
