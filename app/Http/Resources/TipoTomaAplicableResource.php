<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TipoTomaAplicableResource extends JsonResource
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
            "id_modelo" => $this->id_modelo,
            "modelo_origen" => $this->modelo_origen,
            "id_tipo_toma" => $this->id_tipo_toma
        ];
    }
}
