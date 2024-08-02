<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CorreccionSolicitudResource extends JsonResource
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
            "id_tipo" => $this->id_tipo,
            "id_empleado_solicita" => $this->id_empleado_solicita,
            "id_empleado_registra" => $this->id_empleado_registra,
            "tipo_correccion" => $this->tipo_correccion,
            "fecha_solicitud" => $this->fecha_solicitud,
            "fecha_correccion" => $this->fecha_correccion,
            "comentario" => $this->comentario,

            
        ];
        //return parent::toArray($request);
    }
}
