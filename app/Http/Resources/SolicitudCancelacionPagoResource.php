<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudCancelacionPagoResource extends JsonResource
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
            'id_solicitante' => $this->id_solicitante,
            'solicitante' => $this->solicitante->nombre, // Asumiendo que tienes una relación solicitante
            'id_revisor' => $this->id_revisor,
            'revisor' => $this->revisor ? $this->revisor->nombre : null, // Si tienes una relación revisor
            'id_caja' => $this->id_caja,
            'folio' => $this->folio,
            'estado' => $this->estado,
            'fecha_solicitud' => $this->created_at->format('Y-m-d H:i'),
            'fecha_actualizacion' => $this->updated_at->format('Y-m-d H'),
        ];
    }
}
