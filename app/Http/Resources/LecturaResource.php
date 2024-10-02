<?php

namespace App\Http\Resources;

use App\Models\DatoFiscal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LecturaResource extends JsonResource
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
            'id_operador' => $this->id_operador,
            'operador' => new OperadorResource($this->whenLoaded('operador')),
            'id_toma' => $this->id_toma,
            'toma' => new TomaResource($this->whenLoaded('toma')),
            'id_periodo' => $this->id_periodo,
            'periodo' => new PeriodoResource($this->whenLoaded('periodo')),
            'id_origen' => $this->id_origen,
            'modelo_origen' => $this->modelo_origen,
            'origen' => $this->whenLoaded('origen'), // Puedes usar un resource específico si es necesario
            'id_anomalia' => $this->id_anomalia,
            'anomalia' => new AnomaliaCatalogoResource($this->whenLoaded('anomalia')),
            'lectura' => $this->lectura,
            'comentario' => $this->comentario,
            'fecha_creacion' => $this->created_at->format('Y-m-d H:i'),
            'fecha_actualizacion' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}