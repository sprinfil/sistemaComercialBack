<?php

namespace App\Http\Resources;

use App\Models\Contrato;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactibilidadResource extends JsonResource
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
            "id_contrato" => $this->id_contrato,
            "agua_estado_factible"=>$this->agua_estado_factible,
            "alcantarillado_estado_factible"=>$this->alc_estado_factible,
            "derechos_conexion" => $this->derechos_conexion,
            "contrato" => ContratoResource::collection($this->whenLoaded('factibilidad')),
        ];
    }
}
