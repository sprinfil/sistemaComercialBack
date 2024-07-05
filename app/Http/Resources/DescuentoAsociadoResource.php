<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DescuentoAsociadoResource extends JsonResource
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
            "id_usuario" => $this->id_usuario,
            "id_toma" => $this->id_toma,
            "id_descuento" => $this->id_descuento,
            "folio" => $this->folio,
            "evidencia" => $this->evidencia,
        ];
    }
}
