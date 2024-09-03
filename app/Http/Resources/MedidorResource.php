<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedidorResource extends JsonResource
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
            "numero_serie" => $this->numero_serie,
            "marca" => $this->marca,
            "diametro" => $this->diametro,
            "tipo" => $this->tipo,
            "estatus" => $this->estatus,
        ];
        //return parent::toArray($request);
    }
}
