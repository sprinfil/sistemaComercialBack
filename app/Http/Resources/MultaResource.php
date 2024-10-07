<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MultaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'id_multado'=>$this->id_multado,
            'id_catalogo_multa'=>$this->id_catalogo_multa,
            'id_operador'=>$this->id_operador,
            'id_revisor'=>$this->id_revisor,
            'modelo_multado'=>$this->modelo_multado,
            'motivo'=>$this->motivo,
            'fecha_solicitud'=>$this->fecha_solicitud,
            'estado'=>$this->estado
        ];
    }
}
