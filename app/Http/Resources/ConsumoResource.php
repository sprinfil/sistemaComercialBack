<?php

namespace App\Http\Resources;

use App\Models\DatoFiscal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsumoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_toma' => $this->id_toma,
            'toma' => new TomaResource($this->whenLoaded('toma')),
            'id_periodo' => $this->id_periodo,
            'periodo' => new PeriodoResource($this->whenLoaded('periodo')),
            'id_lectura_anterior' => $this->id_lectura_anterior,
            'lectura_anterior' => new LecturaResource($this->whenLoaded('lecturaAnterior')),
            'id_lectura_actual' => $this->id_lectura_actual,
            'lectura_actual' => new LecturaResource($this->whenLoaded('lecturaActual')),
            'tipo' => $this->tipo,
            'estado' => $this->estado,
            'consumo' => $this->consumo,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toDateTimeString() : null,
        ];
    }
}