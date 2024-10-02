<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LetraResource  extends JsonResource
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
            'id' => $this->id,
            'id_convenio' => $this->id_convenio,
            'convenio' => new ConvenioResource($this->whenLoaded('Convenio')),
            'estado' => $this->estado,
            'monto' => $this->monto,
            'vigencia' => $this->vigencia,
            'numero_letra' => $this->numero_letra,
            'tipo_letra' => $this->tipo_letra,
            'origen' => $this->whenLoaded('origen'), // Puedes usar un recurso específico si es necesario.
            'cargos_vigentes' => CargoResource::collection($this->whenLoaded('cargosVigentes')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
