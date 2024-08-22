<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CajaResource extends JsonResource
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
            "id_operador" => $this->id_operador,
            "id_caja_catalogo" => $this->id_caja_catalogo,
            "fondo_inicial" => $this->fondo_inicial,
            "fondo_final" => $this->fondo_final,
            "fecha_apertura" => $this->fecha_apertura,
            "fecha_cierre" => $this->fecha_cierre,
        ];
        

    }
}
